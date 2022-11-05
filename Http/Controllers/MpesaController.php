<?php

namespace Modules\Mpesa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Controllers\BaseController;

class MpesaController extends BaseController
{

    public function simulate(Request $request)
    {
        $result = [];
        return response()->json($result);
    }

    public function stkpush(Request $request)
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

    public function validate(Request $request)
    {
        $result = [];
        return response()->json($result);
    }
}
