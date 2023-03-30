<?php

namespace Modules\Mpesa\Classes;

use App\Models\User;
use Modules\Mpesa\Entities\Gateway as DBGateway;
use Modules\Partner\Entities\Partner;

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
                $data['phone'] = $data['partner']->phone;
            }
        }

        $gateways = DBGateway::where(['published' => true])->get();

        $tabs = [];
        foreach ($gateways as $key => $gateway) {

            $data['gateway'] = $gateway;

            if ($gateway->type == 'paybill') {
                if ($gateway->method == 'stkpush') {
                    $stkpush = view('mpesa::stkpush', $data)->toHtml();
                    $tabs[] = ['title' => 'STK Push', 'slug' => 'STKPUSH', 'gateway' => $gateway, 'html' => $stkpush];
                } else {
                    $paybill = view('mpesa::paybill', $data)->toHtml();
                    $tabs[] = ['title' => 'Paybill', 'slug' => 'PAYBILL', 'gateway' => $gateway, 'html' => $paybill];
                }
            } elseif ($gateway->type == 'tillno') {
                if ($gateway->method == 'stkpush') {
                    $stkpush = view('mpesa::stkpush', $data)->toHtml();
                    $tabs[] = ['title' => 'STK Push', 'slug' => 'STKPUSH', 'gateway' => $gateway, 'html' => $stkpush];
                } else {
                    $paybill = view('mpesa::tillno', $data)->toHtml();
                    $tabs[] = ['title' => 'Till No', 'slug' => 'TILLNO', 'gateway' => $gateway, 'html' => $tillno];
                }
            }

        }

        return $tabs;
    }

}
