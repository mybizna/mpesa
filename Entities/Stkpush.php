<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Stkpush extends BaseModel
{

    protected $table = "mpesa_stkpush";

    public $migrationDependancy = [];

    protected $fillable = ['amount', 'phone', 'reference', 'description', 'command', 'gateway_id', 'completed', 'successful', 'merchant_request_id', 'checkout_request_id'];

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
        $table->string('amount');
        $table->string('phone');
        $table->string('reference');
        $table->string('description')->nullable();
        $table->string('command')->nullable();
        $table->string('merchant_request_id')->nullable();
        $table->string('checkout_request_id')->nullable();
        $table->integer('gateway_id')->nullable();
        $table->tinyInteger('completed')->default(false);
        $table->tinyInteger('successful')->default(false);
    }
}
