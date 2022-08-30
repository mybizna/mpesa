<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Gateway extends BaseModel
{

    protected $table = "mpesa_gateway";

    public $migrationDependancy = [];

    protected $fillable = ['title',  'slug', 'ledger_id', 'currency_id', 'description', 'published'];


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
        $table->string('title');
        $table->string('slug');
        $table->integer('ledger_id')->nullable();
        $table->integer('currency_id')->nullable();
        $table->string('description')->nullable();
        $table->tinyInteger('published')->default(false);
    }
}
