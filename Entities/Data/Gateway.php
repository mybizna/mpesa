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

    }
}
