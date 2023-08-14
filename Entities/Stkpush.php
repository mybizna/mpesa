<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Stkpush extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_stkpush";

    /**
     * List of tables names that are need in this model.
     *
     * @var array<string>
     */
    public array $migrationDependancy = [];

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['amount', 'phone', 'reference', 'description', 'command', 'gateway_id', 'completed', 'successful', 'merchant_request_id', 'checkout_request_id'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['reference'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array <string>
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * List of fields to be migrated to the datebase when creating or updating model during migration.
     *
     * @param Blueprint $table
     * @return void
     */
    public function fields(Blueprint $table): void
    {
        $this->fields->increments('id')->html('text');
        $this->fields->string('amount')->html('text');
        $this->fields->string('phone')->html('text');
        $this->fields->string('reference')->html('textarea');
        $this->fields->string('description')->nullable()->html('textarea');
        $this->fields->string('command')->nullable()->html('text');
        $this->fields->string('merchant_request_id')->nullable()->html('text');
        $this->fields->string('checkout_request_id')->nullable()->html('text');
        $this->fields->integer('gateway_id')->nullable()->html('recordpicker')->table(['mpesa', 'gateway']);
        $this->fields->tinyInteger('completed')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('successful')->nullable()->default(0)->html('switch');
    }
}
