<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Url extends BaseModel
{

    protected $table = "mpesa_url";

    public $migrationDependancy = [];

    protected $fillable = ['conf', 'val'];


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
        $table->string('val');
        $table->string('conf');
        $table->tinyInteger('published')->default(false);
    }
}
