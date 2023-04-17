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

        error_log(json_encode($data));
        Log::info(json_encode($data));

        $slug = $data['slug'] ?? "paybill_express";

        $mpesa = new Mpesa($slug);

        $simulate = $mpesa->simulate($data['phone'], $data['amount'], $data['account']);

        return response()->json($simulate);
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

        $mpesa = new Mpesa();

        $payment = $mpesa->savePayment($data);

        return response()->json($payment);
    }
    public function paybill(Request $request)
    {
        $subscription = new Subscription();

        //$data = $subscription->processData($request);
        $data = Session::get('subscription_data');

        $result = $subscription->paybill($data);

        if ($result == true) {
            return redirect()->route('isp_access_thankyou');
        }

        return redirect()->route('isp_access_payment');
    }

    public function tillno(Request $request)
    {
        $subscription = new Subscription();

       // $data = $subscription->processData($request);
        $data = Session::get('subscription_data');

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
            
            if ($validate_stkpush['successful']) {
                $data['validate_stkpush'] = $validate_stkpush;
                $data['verified'] = 1;
            } else {
                $data['verified'] = 0;
            }
            
        } else {

            $stkpush = $mpesa->stkpush($data['phone'], $invoice->total, $invoice->title, $data['account']);

            $request_sent = ($stkpush) ? 1 : 0;
          
            if ($stkpush) {
                $data['stkpush'] = [
                    'checkout_request_id' => $stkpush->checkout_request_id,
                    'id' => $stkpush->id,
                    'request_sent' => $request_sent,
                    'command' => $stkpush->command,
                    'merchant_request_id' => $stkpush->merchant_request_id,
                ];
            }
        }

        return response()->json($data);

    }

}
