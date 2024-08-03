<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Classes\Migration;
use Modules\Base\Entities\BaseModel;

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
     * List of fields to be migrated to the datebase when creating or updating model during migration.
     *
     * @param Blueprint $table
     * @return void
     */
    public function fields(Blueprint $table = null): void
    {
        $this->fields = $table ?? new Blueprint($this->table);
        
        $this->fields->increments('id')->html('hidden');
        $this->fields->string('amount')->html('number');
        $this->fields->string('phone')->html('text');
        $this->fields->string('reference')->nullable()->html('text');
        $this->fields->string('description')->nullable()->html('textarea');
        $this->fields->integer('gateway_id')->nullable()->html('recordpicker')->relation(['mpesa', 'gateway']);
        $this->fields->tinyInteger('completed')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('successful')->nullable()->default(0)->html('switch');
    }





}
