<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Stkpush extends BaseModel
{

    protected $table = "mpesa_stkpush";

    public $migrationDependancy = [];

    protected $fillable = ['amount', 'phone', 'reference', 'description', 'command', 'gateway_id', 'completed', 'successful'];


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
        $table->string('amount');
        $table->string('phone');
        $table->string('reference');
        $table->string('description');
        $table->string('command');
        $table->integer('gateway_id')->nullable();
        $table->tinyInteger('completed')->default(false);
        $table->tinyInteger('successful')->default(false);
    }
}
