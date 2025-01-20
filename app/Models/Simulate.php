<?php
namespace Modules\Mpesa\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Models\BaseModel;
use Modules\Mpesa\Models\Gateway;

class Simulate extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_simulate";

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['amount', 'phone', 'reference', 'description', 'gateway_id', 'completed', 'successful'];

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
        $table->string('reference')->nullable();
        $table->string('description')->nullable();
        $table->unsignedBigInteger('gateway_id')->nullable();
        $table->tinyInteger('completed')->nullable()->default(0);
        $table->tinyInteger('successful')->nullable()->default(0);

    }

    public function post_migration(Blueprint $table): void
    {
        $table->foreign('gateway_id')->references('id')->on('mpesa_gateway')->onDelete('set null');
    }
}
