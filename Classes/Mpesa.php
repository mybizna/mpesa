<?php

use Illuminate\Support\Str;
use Modules\Mpesa\Entities\Gateway;
use Modules\Mpesa\Entities\Webhook;
use SmoDav\Mpesa\Laravel\Facades\Registrar;
use SmoDav\Mpesa\Laravel\Facades\Simulate;
use SmoDav\Mpesa\Laravel\Facades\STK;

class Mpesa
{
    public function setup()
    {
        $return_url = Str::of(config('mpesa.return_url'))->rtrim();
        $business_code = config('mpesa.return_url');

        $conf = $return_url . '/confirm';
        $val = $return_url . '/validate';

        $gateways = Gateway::where(['published' => true])->get();

        foreach ($gateways as $key => $gateway) {
            $webhook = Webhook::where(['conf' => $conf, 'shortcode' => $gateway->shortcode])->first();

            if (!$webhook) {
                Webhook::create(['conf' => $conf, 'val' => $val, 'shortcode' => $gateway->shortcode, 'published' => true]);
            
                $response = Registrar::register($gateway->shortcode)
                    ->onConfirmation($conf)
                    ->onValidation($val)
                    ->submit();                
            }
        }

    }

    public function simulate($phone, $amount)
    {
        $response = Simulate::request($amount)
            ->from($phone)
            ->usingReference($slug)
            ->push();
    }

    public function stkpush($phone, $amount, $slug)
    {
        $response = STK::request($amount)
            ->from($phone)
            ->usingReference($slug, 'Account Slug')
            ->push();
    }

    public function validateStkpush($stk_id)
    {
        $response = STK::validate($stk_id);
    }

    public function buygoods($phone, $amount, $slug)
    {
        $response = STK::request($amount)
            ->from($phone)
            ->usingReference($slug, 'Account Slug')
            ->setCommand(STK::CUSTOMER_BUYGOODS_ONLINE)
            ->push();
    }

}
