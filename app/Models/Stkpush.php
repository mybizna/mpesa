<?php

namespace Modules\Mpesa\Models;

use Modules\Base\Models\BaseModel;
use Modules\Mpesa\Models\Gateway;

class Stkpush extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_stkpush";

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['amount', 'phone', 'reference', 'description', 'command', 'gateway_id', 'completed', 'successful', 'merchant_request_id', 'checkout_request_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array <string>
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Add relationship to Gateway
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }

}
