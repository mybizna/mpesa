<?php

use Modules\Account\Classes\Ledger;
use Modules\Account\Classes\Gateway;

if (!Schema::hasTable('account_ledger') || !Schema::hasTable('account_gateway')) {
    return [];
}

$ledger = new Ledger();
$gateway = new Gateway();

$mpesa_ledger = @$ledger->getLedgerBySlug('mpesa');
$mpesa_gateway = @$gateway->getGatewayBySlug('mpesa');

$mpesa_ledger_id = (isset($mpesa_ledger->id)) ? $mpesa_ledger->id : 3;
$mpesa_gateway_id = (isset($mpesa_ledger->id)) ? $mpesa_gateway->id : 7;

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
    'ledger_id' => [
        "title" => "Ledger Id",
        "type" => "recordpicker",
        "value" => $mpesa_ledger_id,
        "comp_url" => "account/admin/ledger/list.vue",
        "setting" => [
            'path_param' => ["account", "ledger"],
            'fields' => ['name', 'slug', 'chart_id__account_chart_of_account__name'],
            'template' => '[name] ([slug]) - [chart_id__account_chart_of_account__name]',

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
