<?php

namespace Modules\Mpesa\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Mpesa\Classes\Mpesa;
use Modules\Account\Entities\Invoice as DBInvoice;

class MpesaController extends BaseController
{

    public function simulate(Request $request)
    {
        $result = [];
        return response()->json($result);
    }

    public function callback(Request $request)
    {
        $result = [];
        return response()->json($result);
    }

    public function confirm(Request $request)
    {
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
        $mpesa = new Mpesa();

        $data = $request->all();

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
            $stkpush = $mpesa->stkpush($data['phone'], $invoice->total, $invoice->title);
            $data['stkpush'] = $stkpush;

            if ($stkpush) {
                $data['request_sent'] = 1;
            } else {
                $data['request_sent'] = 0;
            }
        }

        return response()->json($data);

    }

    public function validate(Request $request)
    {
        $result = [];
        return response()->json($result);
    }
}
