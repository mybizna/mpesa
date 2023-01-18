<?php

namespace Modules\Mpesa\Classes;

use Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\Account\Classes\Ledger;
use Modules\Account\Classes\Payment;
use Modules\Mpesa\Entities\Gateway as DBGateway;
use Modules\Mpesa\Entities\Simulate as DBSimulate;
use Modules\Mpesa\Entities\Stkpush as DBStkpush;
use Modules\Mpesa\Entities\Webhook as DBWebhook;
use SmoDav\Mpesa\Laravel\Facades\Simulate;
use SmoDav\Mpesa\Laravel\Facades\STK;

class Mpesa
{

    public $slug;

    public function __construct($slug = '')
    {
        if ($slug == '') {
            $this->slug = Config::get('mpesa.default');
        } else {
            $this->slug = $slug;
        }

        $this->setup();
    }

    public function setup()
    {
        $return_url = rtrim(URL::to(''), '/');

        $confirmation_url = $return_url . '/safaricom/confirm';
        $validation_url = $return_url . '/safaricom/validate';
        $stkpush_url = $return_url . '/safaricom/stkpush';
        $reversal_queue_url = $return_url . '/safaricom/reversal_queue';
        $reversal_result_url = $return_url . '/safaricom/reversal_result';

        Config::set("mpesa.accounts.return_url", $return_url);
        Config::set("mpesa.accounts.confirmation_url", $confirmation_url);
        Config::set("mpesa.accounts.validation_url", $validation_url);
        Config::set("mpesa.accounts.stkpush_url", $stkpush_url);
        Config::set("mpesa.accounts.reversal_queue_url", $reversal_queue_url);
        Config::set("mpesa.accounts.reversal_result_url", $reversal_result_url);

        $gateways = DBGateway::where(['published' => true])->get();

        foreach ($gateways as $key => $gateway) {

            if (!$gateway->published) {
                continue;
            }

            Config::set("mpesa.accounts.$gateway->slug.sandbox", $gateway->sandbox);
            Config::set("mpesa.accounts.$gateway->slug.key", $gateway->consumer_key);
            Config::set("mpesa.accounts.$gateway->slug.secret", $gateway->consumer_secret);
            Config::set("mpesa.accounts.$gateway->slug.initiator", $gateway->initiator_name);
            Config::set("mpesa.accounts.$gateway->slug.id_validation_callback", $validation_url);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.paybill", $gateway->party_a);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.shortcode", $gateway->business_shortcode);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.passkey", $gateway->passkey);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.callback", $confirmation_url);

            if ($gateway->default) {
                Config::set("mpesa.default", $gateway->slug);
            }

            if ($gateway->method == 'sending') {

                $webhook = DBWebhook::where(['confirmation_url' => $confirmation_url, 'shortcode' => $gateway->business_shortcode])->first();

                if (!$webhook) {
                    try {
                        $mpesacall = new MpesaCall($gateway->slug);

                        $response = $mpesacall->registerUrl();

                        if ($response->ResponseCode == 0) {
                            DBWebhook::create(['confirmation_url' => $confirmation_url, 'validation_url' => $validation_url, 'shortcode' => $gateway->business_shortcode, 'slug' => $gateway->slug, 'published' => true]);
                        }

                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }

        }

    }

    public function simulate($phone, $amount, $slug)
    {

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);
        $gateway_id = $this->getGateway();

        $response = Simulate::request($amount)
            ->from($phone)
            ->usingAccount($this->slug)
            ->usingReference('Some Reference')
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

    public function stkpush($phone, $amount, $desc, $account, $command = 'paybill')
    {

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);
        $gateway_id = $this->getGateway();

        $response = '';

        $mpesa_call = new MpesaCall($this->slug);

        $response = STK::request($amount)
            ->from($phone)
            ->usingAccount($this->slug)
            ->usingReference(substr($account, 0, 12), substr($desc, 0, 12))
            ->push();

        if (!isset($response->errorCode) && $response->ResponseCode == 0) {
            $stkpush = DBStkpush::create(
                [
                    'amount' => $amount,
                    'phone' => $phone,
                    'reference' => $desc,
                    'description' => $desc,
                    'command' => $command,
                    'merchant_request_id' => $response->MerchantRequestID,
                    'checkout_request_id' => $response->CheckoutRequestID,
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
