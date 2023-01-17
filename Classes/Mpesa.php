<?php

namespace Modules\Mpesa\Classes;

use Config;
use Illuminate\Support\Str;
use Modules\Account\Classes\Ledger;
use Modules\Account\Classes\Payment;
use Modules\Mpesa\Entities\Gateway as DBGateway;
use Modules\Mpesa\Entities\Simulate as DBSimulate;
use Modules\Mpesa\Entities\Stkpush as DBStkpush;
use Modules\Mpesa\Entities\Webhook as DBWebhook;

class Mpesa
{
    public function setup()
    {

        $return_url = url()->rtrim('/');

        $confirmation_url = $return_url . '/mpesa/confirm';
        $validation_url = $return_url . '/mpesa/validate';
        $stkpush_url = $return_url . '/mpesa/stkpush';
        $reversal_queue_url = $return_url . '/mpesa/reversal_queue';
        $reversal_result_url = $return_url . '/mpesa/reversal_result';

        Config::set("mpesa.default", $gateway->slug);
        Config::set("mpesa.accounts.confirmation_url", $confirmation_url);
        Config::set("mpesa.accounts.validation_url", $validation_url);
        Config::set("mpesa.accounts.stkpush_url", $stkpush_url);
        Config::set("mpesa.accounts.reversal_queue_url", $reversal_queue_url);
        Config::set("mpesa.accounts.reversal_result_url", $reversal_result_url);

        $gateways = DBGateway::where(['published' => true])->get();

        foreach ($gateways as $key => $gateway) {

            $new_validation_url = $validation_url;

            Config::set("mpesa.accounts.$gateway->slug.type", $gateway->type);
            Config::set("mpesa.accounts.$gateway->slug.method", $gateway->method);
            Config::set("mpesa.accounts.$gateway->slug.consumer_key", $gateway->consumer_key);
            Config::set("mpesa.accounts.$gateway->slug.consumer_secret", $gateway->consumer_secret);
            Config::set("mpesa.accounts.$gateway->slug.initiator_name", $gateway->initiator_name);
            Config::set("mpesa.accounts.$gateway->slug.initiator_password", $gateway->initiator_password);
            Config::set("mpesa.accounts.$gateway->slug.party_a", $gateway->party_a);
            Config::set("mpesa.accounts.$gateway->slug.party_b", $gateway->party_b);
            Config::set("mpesa.accounts.$gateway->slug.phone_number", $gateway->phone_number);
            Config::set("mpesa.accounts.$gateway->slug.business_shortcode", $gateway->business_shortcode);
            Config::set("mpesa.accounts.$gateway->slug.passkey", $gateway->passkey);
            Config::set("mpesa.accounts.$gateway->slug.ledger_id", $gateway->ledger_id);
            Config::set("mpesa.accounts.$gateway->slug.sandbox", $gateway->sandbox);
            Config::set("mpesa.accounts.$gateway->slug.default", $gateway->default);
            Config::set("mpesa.accounts.$gateway->slug.published", $gateway->published);

            if ($gateway->default) {
                Config::set("mpesa.default", $gateway->slug);
            }

            if ($gateway->method == 'sending') {
                
                $webhook = DBWebhook::where(['confirmation_url' => $confirmation_url, 'shortcode' => $gateway->shortcode, 'slug' => $gateway->slug])->first();

                if (!$webhook) {
                    try {
                        $response = Registrar::register($gateway->shortcode)
                            ->usingAccount($gateway->slug)
                            ->onConfirmation($confirmation_url)
                            ->onValidation($validation_url)
                            ->submit();

                        if ($response->ResponseCode == 0) {
                            DBWebhook::create(['confirmation_url' => $confirmation_url, 'validation_url' => $validation_url, 'shortcode' => $gateway->shortcode, 'slug' => $gateway->slug, 'published' => true]);
                        }
                    } catch (\Throwable$th) {
                        //throw $th;
                    }

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
            $stkpush = DBStkpush::create(
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

            return $stkpush;
        }

        return false;
    }

    public function validateStkpush($checkout_request_id, $phone, $invoice)
    {
        $payment = new Payment();
        $ledger_cls = new Ledger();

        $this->setup();

        $partner_id = $invoice->partner_id;
        $title = $invoice->title;
        $amount = $invoice->total;
        $invoice_id = $invoice->id;

        $response = STK::validate($checkout_request_id);

        if (!isset($response->errorCode) && $response->ResultCode == 0) {

            $ledger = $ledger_cls->getLedgerBySlug('mpesa');

            $stkpush = DBStkpush::where('checkout_request_id', $checkout_request_id)->first();
            $stkpush->completed = true;
            $stkpush->successful = true;
            $stkpush->save();

            $title = 'Payment for : ' . $phone . ' ' . $amount . ' - ' . $title;

            $payment_data = $payment->addPayment($partner_id, $title, $amount, do_reconcile_invoices:true, ledger_id:$ledger->id, invoice_id:$invoice_id);

            return $payment_data;
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
    public function createValidationResponse($result_code, $result_description)
    {
        $result = json_encode(["ResultCode" => $result_code, "ResultDesc" => $result_description]);
        $response = new Response();
        $response->headers->set("Content-Type", "application/json; charset=utf-8");
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
        $content = json_decode($request->getContent());
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
        $response->headers->set("Content-Type", "text/xml; charset=utf-8");
        $response->setContent(json_encode(["C2BPaymentConfirmationResult" => "Success"]));
        return $response;
    }

}
