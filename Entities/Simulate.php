<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Simulate extends BaseModel
{

    protected $table = "mpesa_simulate";

    public $migrationDependancy = [];

    protected $fillable = ['amount', 'phone', 'reference', 'description', 'gateway_id', 'completed', 'successful'];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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
        $table->string('description');
        $table->integer('gateway_id')->nullable();
        $table->tinyInteger('completed')->default(false);
        $table->tinyInteger('successful')->default(false);
    }
}
