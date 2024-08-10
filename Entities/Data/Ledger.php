<?php

namespace Modules\Mpesa\Models\Data;

use Illuminate\Support\Facades\DB;
use Modules\Base\Classes\Datasetter;

class Ledger
{
    /**
     * Set ordering of the Class to be migrated.
     *
     * @var int
     */
    public $ordering = 5;

    /**
     * Run the database seeds with system default records.
     *
     * @param Datasetter $datasetter
     *
     * @return void
     */
    public function data(Datasetter $datasetter): void
    {
        $asset_chart_id = DB::table('account_chart_of_account')
            ->where('slug', 'asset')->value('id');

        $datasetter->add_data('account', 'ledger', 'slug', [
            "chart_id" => $asset_chart_id,
            "name" => "MPesa",
            "slug" => "mpesa",
            "code" => "93",
            "system" => "0",
        ]);

    }
}
