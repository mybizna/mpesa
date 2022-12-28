<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Payment extends BaseModel
{

    protected $table = "mpesa_payment";

    public $migrationDependancy = [];

    protected $fillable = ['trans_type',  'trans_id', 'trans_time', 'trans_amount', 'business_short_code',  'bill_ref_number', 'invoice_number', 'org_account', 'third_party_id', 'msisdn', 'first_name', 'middle_name', 'last_name','published'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_by', 'updated_by', 'deleted_at'];

    /**
     * List of fields for managing postings.
     *
     * @param Blueprint $table
     * @return void
     */
    public function migration(Blueprint $table)
    {
        $table->increments('id');
        $table->string('trans_type');
        $table->string('trans_id');
        $table->string('trans_time');
        $table->string('trans_amount');
        $table->string('business_short_code');
        $table->string('bill_ref_number');
        $table->string('invoice_number');
        $table->string('org_account');
        $table->string('third_party_id');
        $table->string('msisdn');
        $table->string('first_name');
        $table->string('middle_name');
        $table->string('last_name');
        $table->tinyInteger('published')->default(false);
    }
}
