<?php

namespace Modules\Mpesa\Entities\Data;

use Illuminate\Support\Facades\DB;
use Modules\Base\Classes\Datasetter;

class Gateway
{
    /**
     * Set ordering of the Class to be migrated.
     * 
     * @var int
     */
    public $ordering = 7;

    /**
     * Run the database seeds with system default records.
     *
     * @param Datasetter $datasetter
     *
     * @return void
     */
    public function data(Datasetter $datasetter): void
    {
        $ledger_id = DB::table('account_ledger')->where('slug', 'mpesa')->value('id');

        $datasetter->add_data('account', 'gateway', 'slug', [
            "title" => "MPesa",
            "slug" => "mpesa",
            "ledger_id" => $ledger_id,
            "instruction" => "Mpesa",
            "module" => "Mpesa",
            "ordering" => 4,
            "is_default" => false,
            "is_hidden" => false,
            "is_hide_in_invoice" => false,
            "published" => true,
        ]);

        $datasetter->add_data('mpesa', 'gateway', 'slug', [
            "title" => "Sandbox MPesa Express",
            "description" => "Sandbox MPesa Express ",
            "slug" => "sandbox_express",
            "consumer_key" => "QGa0eEKca1d3NxGktFFabLqT20Z0YLzo",
            "consumer_secret" => "4EJjtH4UHYTpmGNm",
            "initiator_name" => "testapi",
            "initiator_password" => "Safaricom999!*!",
            "type" => "paybill",
            "method" => "stkpush",
            "party_a" => "600998",
            "party_b" => "600000",
            "phone_number" => "254708374149",
            "business_shortcode" => "174379",
            "passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
            "ledger_id" => $ledger_id,
            "sandbox" => true,
            "default" => true,
            "published" => true,
        ]);

        $datasetter->add_data('mpesa', 'gateway', 'slug', [
            "title" => "MPesa Paybill",
            "description" => "MPesa Paybill ",
            "slug" => "paybill_express",
            "consumer_key" => "QGa0eEKca1d3NxGktFFabLqT20Z0YLzo",
            "consumer_secret" => "4EJjtH4UHYTpmGNm",
            "initiator_name" => "testapi",
            "initiator_password" => "Safaricom999!*!",
            "type" => "paybill",
            "method" => "sending",
            "party_a" => "600990",
            "party_b" => "600000",
            "phone_number" => "254708374149",
            "business_shortcode" => "174379",
            "passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
            "ledger_id" => $ledger_id,
            "sandbox" => true,
            "default" => false,
            "published" => true,
        ]);

    }
}
