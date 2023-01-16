<?php

namespace Modules\Mpesa\Classes;

class Gateway
{
    public function getGatewayTab($gateway)
    {
        $tabs = [];

        $tabs[] = ['title' => 'STK Push', 'slug' => 'STKPUSH', 'html' => '<p>STK Push</p>'];
        $tabs[] = ['title' => 'Till No', 'slug' => 'TILLNO', 'html' => '<p>Till No</p>'];
        $tabs[] = ['title' => 'Paybill', 'slug' => 'PAYBILL', 'html' => '<p>Paybill</p>'];

        return $tabs;
    }

}
