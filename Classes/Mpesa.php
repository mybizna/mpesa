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

        $conf = $return_url . '/mpesa/confirm';
        $val = $return_url . '/mpesa/validate';

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


    /**
     * J-son Response to M-pesa API feedback - Success or Failure
     */
    public function createValidationResponse($result_code, $result_description){
        $result=json_encode(["ResultCode"=>$result_code, "ResultDesc"=>$result_description]);
        $response = new Response();
        $response->headers->set("Content-Type","application/json; charset=utf-8");
        $response->setContent($result);
        return $response;
    }

    public function mpesaValidation(Request $request)
    {
        $result_code = "0";
        $result_description = "Accepted validation request.";
        return $this->createValidationResponse($result_code, $result_description);
    }
    
    public function mpesaConfirmation(Request $request)
    {
        $content=json_decode($request->getContent());
        $mpesa_transaction = new MpesaTransaction();
        $mpesa_transaction->TransactionType = $content->TransactionType;
        $mpesa_transaction->TransID = $content->TransID;
        $mpesa_transaction->TransTime = $content->TransTime;
        $mpesa_transaction->TransAmount = $content->TransAmount;
        $mpesa_transaction->BusinessShortCode = $content->BusinessShortCode;
        $mpesa_transaction->BillRefNumber = $content->BillRefNumber;
        $mpesa_transaction->InvoiceNumber = $content->InvoiceNumber;
        $mpesa_transaction->OrgAccountBalance = $content->OrgAccountBalance;
        $mpesa_transaction->ThirdPartyTransID = $content->ThirdPartyTransID;
        $mpesa_transaction->MSISDN = $content->MSISDN;
        $mpesa_transaction->FirstName = $content->FirstName;
        $mpesa_transaction->MiddleName = $content->MiddleName;
        $mpesa_transaction->LastName = $content->LastName;
        $mpesa_transaction->save();
        // Responding to the confirmation request
        $response = new Response();
        $response->headers->set("Content-Type","text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult"=>"Success"]));
        return $response;
    }

}
