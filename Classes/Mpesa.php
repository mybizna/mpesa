<?php

namespace Modules\Mpesa\Classes;

use Illuminate\Support\Str;
use Modules\Mpesa\Entities\Gateway as DBGateway;
use Modules\Mpesa\Entities\Simulate as DBSimulate;
use Modules\Mpesa\Entities\Webhook as DBWebhook;
use Modules\Mpesa\Entities\Stkpush as DBStkpush;
use SmoDav\Mpesa\Laravel\Facades\Registrar;
use SmoDav\Mpesa\Laravel\Facades\Simulate;
use SmoDav\Mpesa\Laravel\Facades\STK;

class Mpesa
{
    public function setup()
    {
        $return_url = Str::of(config('mpesa.return_url'))->rtrim('/');
        $business_code = config('mpesa.default');

        $conf = $return_url . '/confirm';
        $val = $return_url . '/validate';

        $gateways = DBGateway::where(['published' => true])->get();
        
        foreach ($gateways as $key => $gateway) {
            $webhook = DBWebhook::where(['conf' => $conf, 'shortcode' => $gateway->shortcode])->first();

            if (!$webhook) {
                DBWebhook::create(['conf' => $conf, 'val' => $val, 'shortcode' => $gateway->shortcode, 'published' => true]);

                $response = Registrar::register($gateway->shortcode)
                    ->onConfirmation($conf)
                    ->onValidation($val)
                    ->submit();
            }
        }

    }

    public function simulate($phone, $amount, $slug)
    {
        $this->setup();

        $gateway_id = $this->getGateway();

        DBSimulate::create(
            [
                'amount' => $amount,
                'phone' => $phone,
                'reference' => $slug,
                'description' => $slug,
                'gateway_id' => $gateway_id,
            ]
        );

        $response = Simulate::request($amount)
            ->from($phone)
            ->usingReference($slug)
            ->push();
    }

    public function stkpush($phone, $amount, $slug, $command = 'paybill')
    {
        
        $this->setup();
        print_r('$stkpush'); exit;
       
        $gateway_id = $this->getGateway();

        DBStkpush::create(
            [
                'amount' => $amount,
                'phone' => $phone,
                'reference' => $slug,
                'description' => $slug,
                'command' => $command,
                'gateway_id' => $gateway_id,
            ]
        );

        if ($command == 'buygood') {
            $response = STK::request($amount)
                ->from($phone)
                ->usingReference($slug, 'Account Slug')
                ->setCommand(STK::CUSTOMER_BUYGOODS_ONLINE)
                ->push();
        } else {
            $response = STK::request($amount)
                ->from($phone)
                ->usingReference($slug, 'Account Slug')
                ->push();
        }

    }

    public function validateStkpush($stk_id)
    {
        $this->setup();

        $response = STK::validate($stk_id);
    }

    public function buygoods($phone, $amount, $slug)
    {
        $this->stkpush($phone, $amount, $slug,);
    }

    public function getGateway($type = 'express')
    {
        $business_code = config('mpesa.default');

        $gateway = DBGateway::where(['shortcode' => $business_code, 'published' => true])->first();

        if ($gateway) {
            $gateway = DBGateway::where(['published' => true])->first();
        }

        return $gateway->id;

    }

}
