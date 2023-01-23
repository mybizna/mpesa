<?php

namespace Modules\Mpesa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Account\Entities\Invoice as DBInvoice;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Mpesa\Classes\Mpesa;

class MpesaController extends BaseController
{

    public function simulate(Request $request)
    {
        $result = [];

        $data = $request->all();

        $slug = $data['slug']?? "paybill_express";

        $mpesa = new Mpesa($slug);

        $simulate = $mpesa->simulate($data['phone'], $data['amount'], $data['account']);

        return response()->json($result);
    }

    public function validate(Request $request)
    {

        $data = $request->all();

        error_log(json_encode($data));
        Log::info(json_encode($data));

        $path = realpath(base_path()) . '/storage/logs/safaricom_validate.txt';
        touch($path);
        chmod($path, 0775);
        $fp = fopen($path, 'a');
        fwrite($fp, json_encode($data) . PHP_EOL);
        fclose($fp);

        $result = [];

        return response()->json($result);
    }
    public function confirm(Request $request)
    {

        $data = $request->all();

        error_log(json_encode($data));
        Log::info(json_encode($data));

        ['trans_type'=>$data['TransactionType'],  
        'trans_id'=>$data['TransID'], 
        'trans_time'=>$data['TransTime'], 
        'trans_amount'=>$data['TransAmount'], 
        'business_short_code'=>$data['BusinessShortCode'],  
        'bill_ref_number'=>$data['BillRefNumber'], 
        'invoice_number'=>$data['InvoiceNumber'], 
        'org_account'=>$data['OrgAccountBalance'], 
        'third_party_id'=>$data['ThirdPartyTransID'],
         'msisdn'=>$data['MSISDN'], 
         'first_name'=>$data['FirstName'], 
         'middle_name'=>$data['MiddleName'], 
         'last_name'=>$data['LastName'],
         'published'=>1
        ];



        $result = [];

        return response()->json($result);
    }
    public function paybill(Request $request)
    {
        $subscription = new Subscription();

        $data = $subscription->processData($request);

        $result = $subscription->paybill($data);

        if ($result == true) {
            return redirect()->route('isp_access_thankyou');
        }

        return redirect()->route('isp_access_payment');
    }

    public function tillno(Request $request)
    {
        $subscription = new Subscription();

        $data = $subscription->processData($request);

        $result = $subscription->tillno($data);

        if ($result === true) {
            return redirect()->route('isp_access_thankyou');
        }

        return redirect()->route('isp_access_payment');
    }

    public function stkpush(Request $request)
    {

        $data = $request->all();

        $mpesa = new Mpesa($data['slug']);

        $invoice = DBInvoice::where(['id' => $data['invoice_id']])->first();

        if (isset($data['verifying']) && $data['verifying']) {
            $validate_stkpush = $mpesa->validateStkpush($data['checkout_request_id'], $data['phone'], $invoice);
            if ($validate_stkpush) {
                $data['validate_stkpush'] = $validate_stkpush;
                $data['verified'] = 1;
            } else {
                $data['verified'] = 0;
            }
        } else {
            $stkpush = $mpesa->stkpush($data['phone'], $invoice->total, $invoice->title, $data['account']);
            $data['stkpush'] = [
                'checkout_request_id' => $stkpush->checkout_request_id,
                'id' => $stkpush->id,
                'command' => $stkpush->command,
                'merchant_request_id' => $stkpush->merchant_request_id,
            ];

            if ($stkpush) {
                $data['request_sent'] = 1;
            } else {
                $data['request_sent'] = 0;
            }
        }

        return response()->json($data);

    }

}
