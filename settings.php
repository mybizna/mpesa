<?php

use Modules\Account\Classes\Ledger;
use Modules\Account\Classes\Payment;

$ledger = new Ledger();
$payment = new Payment();

$mpesa_ledger = $ledger->getLedgerBySlug('mpesa');
$mpesa_gateway = $payment->getGatewayBySlug('mpesa');

$mpesa_ledger_id = $mpesa_ledger->id;
$mpesa_gateway_id = $mpesa_gateway->id;

return [
    'return_url' => [
        "title" => "Return URL",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'status' => [
        "title" => "Status",
        "type" => "text",
        "value" => "sandbox",
        "category" => "Mpesa",
    ],
    'type' => [
        "title" => "Type",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'customer_key' => [
        "title" => "Customer Key",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'customer_secret' => [
        "title" => "Customer Secret",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'initiator' => [
        "title" => "Initiator Username",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'short_code' => [
        "title" => "Short Code",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'paybill_tillno' => [
        "title" => "Paybill Tillno",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'pass_key' => [
        "title" => "Pass Key",
        "type" => "text",
        "value" => "",
        "category" => "Mpesa",
    ],
    'sandbox_type' => [
        "title" => "Sandbox Type",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'sandbox_customer_key' => [
        "title" => "Sandbox Customer Key",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'sandbox_customer_secret' => [
        "title" => "Sandbox Customer Secret",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'sandbox_initiator' => [
        "title" => "Sandbox Initiator Username",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'sandbox_short_code' => [
        "title" => "Sandbox Short Code",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'sandbox_paybill_tillno' => [
        "title" => "Sandbox Paybill Tillno",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'sandbox_pass_key' => [
        "title" => "Sandbox Pass Key",
        "type" => "text",
        "value" => "",
        "category" => "Sandbox Mpesa",
    ],
    'ledger_id' => [
        "title" => "Ledger Id",
        "type" => "recordpicker",
        "value" => $mpesa_ledger_id,
        "comp_url" => "account/admin/ledger/list.vue",
        "setting" => [
            'path_param' => ["account", "ledger"],
            'fields' => ['name', 'slug', 'chart_id', 'account_chart_of_account__name'],
            'template' => '[name] ([slug]) - [chart_id.account_chart_of_account__name]',

        ],
    ],
    'payment_method_id' => [
        "title" => "Payment Method Id",
        "type" => "recordpicker",
        "value" => $mpesa_gateway_id,
        "comp_url" => "account/admin/gateway/list.vue",
        "setting" => [
            'path_param' => ["account", "gateway"],
            'fields' => ['title'],
            'template' => '[title]',

        ],
    ],
];
