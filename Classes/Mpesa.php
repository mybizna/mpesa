<?php

namespace Modules\Mpesa\Classes;

use Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Modules\Account\Classes\Ledger;
use Modules\Account\Classes\Payment;
use Modules\Account\Entities\Gateway as DBAccGateway;
use Modules\Mpesa\Entities\Gateway as DBGateway;
use Modules\Mpesa\Entities\Payment as DBPayment;
use Modules\Mpesa\Entities\Simulate as DBSimulate;
use Modules\Mpesa\Entities\Stkpush as DBStkpush;
use Modules\Mpesa\Entities\Webhook as DBWebhook;
use Modules\Partner\Classes\Partner;
use SmoDav\Mpesa\Laravel\Facades\Registrar;
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

        $confirmation_url = $return_url . '/kemomo/confirm';
        $validation_url = $return_url . '/kemomo/validate';
        $stkpush_url = $return_url . '/kemomo/stkpush';
        $reversal_queue_url = $return_url . '/kemomo/reversal_queue';
        $reversal_result_url = $return_url . '/kemomo/reversal_result';

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

            $shortcode = $gateway->party_a;

            Config::set("mpesa.accounts.$gateway->slug.sandbox", $gateway->sandbox);
            Config::set("mpesa.accounts.$gateway->slug.key", $gateway->consumer_key);
            Config::set("mpesa.accounts.$gateway->slug.secret", $gateway->consumer_secret);
            Config::set("mpesa.accounts.$gateway->slug.initiator", $gateway->initiator_name);
            Config::set("mpesa.accounts.$gateway->slug.id_validation_callback", $validation_url);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.paybill", $gateway->party_a);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.shortcode", $shortcode);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.passkey", $gateway->passkey);
            Config::set("mpesa.accounts.$gateway->slug.lnmo.callback", $confirmation_url);

            if ($gateway->default) {
                Config::set("mpesa.default", $gateway->slug);
            }

            if ($gateway->method == 'sending') {

                $webhook = DBWebhook::where(['confirmation_url' => $confirmation_url, 'shortcode' => $shortcode])->first();

                if (!$webhook) {
                    try {

                        $response = Registrar::register($shortcode)
                            ->usingAccount($gateway->slug)
                            ->onConfirmation($confirmation_url)
                            ->onValidation($validation_url)
                            ->submit();

                        if ($response->ResponseCode == 0) {
                            DBWebhook::create(['confirmation_url' => $confirmation_url, 'validation_url' => $validation_url, 'shortcode' => $shortcode, 'slug' => $gateway->slug, 'published' => true]);
                        }

                    } catch (\Throwable$th) {
                        throw $th;
                    }
                }
            }

        }

    }

    public function savePayment($data)
    {

        $save_data = ['trans_type' => $data['TransactionType'], 'trans_id' => $data['TransID'],
            'trans_time' => $data['TransTime'], 'trans_amount' => $data['TransAmount'],
            'business_short_code' => $data['BusinessShortCode'], 'bill_ref_number' => $data['BillRefNumber'],
            'invoice_number' => $data['InvoiceNumber'], 'org_account' => $data['OrgAccountBalance'],
            'third_party_id' => $data['ThirdPartyTransID'], 'msisdn' => $data['MSISDN'],
            'first_name' => $data['FirstName'], 'middle_name' => $data['MiddleName'],
            'last_name' => $data['LastName'], 'published' => 1,
        ];

        $payment = DBPayment::updateOrCreate($save_data, ['trans_id' => $data['TransID']]);

        if ($payment && !$payment->successful) {

            $payment_cls = new Payment();
            $ledger_cls = new Ledger();
            $partner_cls = new Partner();

            $ledger = $ledger_cls->getLedgerBySlug('mpesa');
            $gateway = DBAccGateway::where('slug', 'mpesa')->first();

            $account = $payment->bill_ref_number;
            $partner = $partner_cls->getPartner($payment->bill_ref_number);

            if ($partner) {
                $partner_id = $partner->id;
                $phone = $payment->msisdn;
                $amount = $payment->trans_amount;

                $title = 'Payment for : ' . $phone . ' ' . $amount . ' - ' . $account;

                $payment_data = $payment_cls->addPayment($partner_id, $title, $amount, do_reconcile_invoices:true, gateway_id:$gateway->id, ledger_id:$ledger->id, code:$save_data['trans_id']);

                $payment->completed = true;
                $payment->successful = true;
                $payment->save();
            }
        }

    }

    public function stkpush($phone, $amount, $desc, $account, $command = 'paybill')
    {

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);
        $gateway_id = $this->getGateway();

        $response = '';

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

        $stkpush = DBStkpush::where('checkout_request_id', $checkout_request_id)->first();

        if (!$stkpush->successful) {
            $response = STK::validate($checkout_request_id);

            if (!isset($response->errorCode) && $response->ResultCode == 0) {

                $ledger = $ledger_cls->getLedgerBySlug('mpesa');
                $gateway = DBAccGateway::where('slug', 'mpesa')->first();

                $title = 'Payment for : ' . $phone . ' ' . $amount . ' - ' . $title;

                $payment_data = $payment->addPayment($partner_id, $title, $amount, do_reconcile_invoices:true, gateway_id:$gateway->id, ledger_id:$ledger->id, invoice_id:$invoice_id, code:$checkout_request_id);

                $stkpush->completed = true;
                $stkpush->successful = true;
                $stkpush->save();

                return $payment_data;
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
    public function simulate($phone, $amount, $account)
    {

        $phone = $this->getPhone($phone);
        $amount = $this->getAmount($amount);
        $gateway_id = $this->getGateway();

        $response = Simulate::request($amount)
            ->from($phone)
            ->usingAccount($this->slug)
            ->usingReference($account)
            ->push();

        if (!isset($response->errorCode) && $response->ResponseCode == 0) {
            DBSimulate::create(
                [
                    'amount' => $amount,
                    'phone' => $phone,
                    'reference' => $account,
                    'description' => $account,
                    'gateway_id' => $gateway_id,
                ]
            );

        }

        return $response;

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
