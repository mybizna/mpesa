<?php

namespace Modules\Mpesa\Models;

use Modules\Base\Models\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Payment extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_payment";

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['trans_type', 'trans_id', 'trans_time', 'trans_amount', 'business_short_code', 'bill_ref_number', 'invoice_number', 'org_account', 'third_party_id', 'msisdn', 'first_name', 'middle_name', 'last_name', 'completed', 'successful', 'published'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array <string>
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    public function migration(Blueprint $table): void
    {
        $table->string('trans_type')->nullable();
        $table->string('trans_id')->nullable();
        $table->string('trans_time')->nullable();
        $table->string('trans_amount')->nullable();
        $table->string('business_short_code')->nullable();
        $table->string('bill_ref_number')->nullable();
        $table->string('invoice_number')->nullable();
        $table->string('org_account')->nullable();
        $table->string('third_party_id')->nullable();
        $table->string('msisdn')->nullable();
        $table->string('first_name')->nullable();
        $table->string('middle_name')->nullable();
        $table->string('last_name')->nullable();
        $table->tinyInteger('published')->nullable()->default(0);
        $table->tinyInteger('completed')->nullable()->default(0);
        $table->tinyInteger('successful')->nullable()->default(0);
    }
}
