<?php

namespace Modules\Mpesa\Classes;

use App\Models\User;
use Modules\Partner\Entities\Partner;
use Modules\Mpesa\Entities\Gateway as DBGateway;

class Gateway
{
    public function getGatewayTab($gateway, $invoice)
    {
        $data = [
            'request_sent' => false,
            'invoice' => $invoice,
            'phone' => '',
            'short_code' => '2323232',
        ];

        if ($invoice->partner_id) {
            $data['partner'] = Partner::where(['id' => $invoice->partner_id])->first();

            if ($data['partner']) {
                $data['user'] = User::where(['email' => $data['partner']->email])->first();
                $data['phone'] =$data['user']->phone;
            }
        }

        $gateways = DBGateway::where(['published' => true])->get();

        $tabs = [];
        foreach ($gateways as $key => $gateway) {

            $data['gateway'] = $gateway;

            if($gateway->type =='stkpush'){
                $stkpush = view('mpesa::stkpush', $data)->toHtml();
                $tabs[] = ['title' => 'STK Push', 'slug' => 'STKPUSH', 'html' => $stkpush];
            } else if($gateway->type =='tillno'){
                $tillno = view('mpesa::tillno', $data)->toHtml();
                $tabs[] = ['title' => 'Till No', 'slug' => 'TILLNO', 'html' => $tillno];
            } else if($gateway->type =='paybill'){
                $paybill = view('mpesa::paybill', $data)->toHtml();
                $tabs[] = ['title' => 'Paybill', 'slug' => 'PAYBILL', 'html' => $paybill];
            }
        }

        return $tabs;
    }

}
