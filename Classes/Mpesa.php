<?php

use SmoDav\Mpesa\Laravel\Facades\Registrar;
use SmoDav\Mpesa\Laravel\Facades\Simulate;
use SmoDav\Mpesa\Laravel\Facades\STK;

class Mpesa
{
    public function setup()
    {

        $conf = 'http://example.com/mpesa/confirm?secret=some_secret_hash_key';
        $val = 'http://example.com/mpesa/validate?secret=some_secret_hash_key';

        $response = Registrar::register(600000)
            ->onConfirmation($conf)
            ->onValidation($val)
            ->submit();
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
