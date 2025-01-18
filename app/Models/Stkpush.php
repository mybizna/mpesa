<?php

namespace Modules\Mpesa\Models;

use Modules\Base\Models\BaseModel;
use Modules\Mpesa\Models\Gateway;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(Gateway::class);
    }


    public function migration(Blueprint $table): void
    {

        $table->string('amount');
        $table->string('phone');
        $table->string('reference');
        $table->string('description')->nullable();
        $table->string('command')->nullable();
        $table->string('merchant_request_id')->nullable();
        $table->string('checkout_request_id')->nullable();
        $table->foreignId('gateway_id')->nullable()->constrained('mpesa_gateway')->onDelete('set null');
        $table->tinyInteger('completed')->nullable()->default(0);
        $table->tinyInteger('successful')->nullable()->default(0);

    }
}
