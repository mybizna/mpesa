<?php

namespace Modules\Mpesa\Entities\Data;

use Illuminate\Support\Facades\DB;
use Modules\Base\Classes\Datasetter;

class Gateway
{

    public $ordering = 7;

    public function data(Datasetter $datasetter)
    {
        $ledger_id = DB::table('account_ledger')->where('slug', 'mpesa')->value('id');
        
        $datasetter->add_data('account', 'gateway', 'slug', [
            "title" => "MPesa",
            "slug" => "mpesa",
            "ledger_id" => $ledger_id,
            "instruction" => "",
            "ordering" => 0,
            "is_default" => true,
            "published" => true
        ]);
        
        $datasetter->add_data('mpesa', 'gateway', 'slug', [
            "title" => "Sandbox MPesa Express",
            "description" => "Sandbox MPesa Express",
            "slug" => "sandbox_express",
            "key" => "kIey5p9dPdxbfyaAlvmmQVLU7XX9yQoD",
            "secret" => "QuRR0jDA2iPkBJFb",
            "initiator" => "mybizna",
            "till_bill_no" => "paybill",
            "passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
            "shortcode" => "174379",
            "ledger_id" => $ledger_id,
            "sandbox" => true,
            "default" => true,
            "published" => true
        ]);

        $datasetter->add_data('mpesa', 'gateway', 'slug', [
            "title" => "MPesa Paybill",
            "description" => "MPesa Paybill",
            "slug" => "paybill_express",
            "key" => "kIey5p9dPdxbfyaAlvmmQVLU7XX9yQoD",
            "secret" => "QuRR0jDA2iPkBJFb",
            "initiator" => "mybizna",
            "till_bill_no" => "paybill",
            "passkey" => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
            "shortcode" => "600978",
            "ledger_id" => $ledger_id,
            "sandbox" => true,
            "default" => false,
            "published" => true
        ]);

    }
}
