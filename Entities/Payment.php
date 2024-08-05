<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;

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

}
