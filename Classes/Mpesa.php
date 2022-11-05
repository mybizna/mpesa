<?php

namespace Modules\Mpesa\Classes;

use Illuminate\Support\Str;
use Modules\Account\Classes\Payment;
use Modules\Account\Classes\Ledger;
use Modules\Mpesa\Entities\Gateway as DBGateway;
use Modules\Mpesa\Entities\Simulate as DBSimulate;
use Modules\Mpesa\Entities\Stkpush as DBStkpush;
use Modules\Mpesa\Entities\Webhook as DBWebhook;
use SmoDav\Mpesa\Laravel\Facades\Registrar;
use SmoDav\Mpesa\Laravel\Facades\Simulate;
use SmoDav\Mpesa\Laravel\Facades\STK;

class Mpesa
{
    public function setup()
    {
        $return_url = Str::of(config('mpesa.return_url'))->rtrim('/');
        $business_code = config('mpesa.default');

        $conf = $return_url . '/confirm';
        $val = $return_url . '/validate';

        $gateways = DBGateway::where(['published' => true])->get();

        foreach ($gateways as $key => $gateway) {
            $webhook = DBWebhook::where(['conf' => $conf, 'shortcode' => $gateway->shortcode])->first();

            if (!$webhook) {
                $response = Registrar::register($gateway->shortcode)
                    ->onConfirmation($conf)
                    ->onValidation($val)
                    ->submit();

                if ($response->ResponseCode == 0) {
                    DBWebhook::create(['conf' => $conf, 'val' => $val, 'shortcode' => $gateway->shortcode, 'published' => true]);
                }

            }
        }

    }

    public function simulate($phone, $amount, $slug)
    {
        $this->setup();

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);
        $gateway_id = $this->getGateway();

        $response = Simulate::request($amount)
            ->from($phone)
            ->usingReference($slug)
            ->push();

        if (!isset($response->errorCode) && $response->ResponseCode == 0) {
            DBSimulate::create(
                [
                    'amount' => $amount,
                    'phone' => $phone,
                    'reference' => $slug,
                    'description' => $slug,
                    'gateway_id' => $gateway_id,
                ]
            );

            return true;
        }

        return false;
    }

    public function stkpush($phone, $amount, $slug, $command = 'paybill')
    {
        $this->setup();

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);
        $gateway_id = $this->getGateway();

        $response = '';

        if ($command == 'buygood') {
            $response = STK::request($amount)
                ->from($phone)
                ->usingReference($slug, 'Account Slug')
                ->setCommand(STK::CUSTOMER_BUYGOODS_ONLINE)
                ->push();
        } else {
            $response = STK::request($amount)
                ->from($phone)
                ->usingReference($slug, 'Account Slug')
                ->push();
        }

        if (!isset($response->errorCode) && $response->ResponseCode == 0) {
            DBStkpush::create(
                [
                    'amount' => $amount,
                    'phone' => $phone,
                    'reference' => $slug,
                    'description' => $slug,
                    'command' => $command,
                    'merchant_request_id' => $response->MerchantRequestID,
                    'checkout_request_id' => $response->CheckoutRequestID,
                    'command' => $command,
                    'gateway_id' => $gateway_id,
                ]
            );

            return true;
        }

        return false;
    }

    public function validateStkpush($phone, $amount, $title, $partner_id, $invoice_id)
    {
        $payment = new Payment();
        $ledger_cls = new Ledger();

        $this->setup();

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);

        $stkpush = DBStkpush::where(['phone' => $phone, 'completed' => false])->orderBy('id', 'DESC')->first();

        if ($stkpush) {
            $response = STK::validate($stkpush->checkout_request_id);
            if (!isset($response->errorCode) && $response->ResultCode == 0) {

                $ledger = $ledger_cls->getLedgerBySlug('mpesa');

                $stkpush->completed = true;
                $stkpush->successful = true;
                //$stkpush->save();

                $title = 'Payment for : ' . $phone . ' ' . $amount . ' - ' . $title;

                $payment->addPayment($partner_id, $title, $amount, do_reconcile_invoices:true, ledger_id:$ledger->id, invoice_id:$invoice_id);

                return true;

            }
        }
        print_r($response);exit;

        return false;
    }

    public function buygoods($phone, $amount, $slug)
    {
        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);

        $this->stkpush($phone, $amount, $slug, );
    }

    public function getAmount($amount)
    {
        return (int) $amount;
    }

    public function getPhone($phone)
    {

        if (Str::length($phone) != 12) {
            $phone = "254" . Str::substr($phone, -9);
        }

        return $phone;
    }

    public function getGateway($type = 'express')
    {
        $slug = config('mpesa.default');

        $gateway = DBGateway::where(['slug' => $slug, 'published' => true])->first();

        if ($gateway) {
            $gateway = DBGateway::where(['published' => true])->first();
        }

        return $gateway->id;

    }

}
