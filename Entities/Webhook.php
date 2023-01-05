<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Webhook extends BaseModel
{

    protected $table = "mpesa_webhook";

    public $migrationDependancy = [];

    protected $fillable = ['conf', 'val', 'shortcode', 'published'];


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
        $table->string('val');
        $table->string('conf');
        $table->string('shortcode');
        $table->tinyInteger('published')->default(false);
    }
}
