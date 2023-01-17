<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Webhook extends BaseModel
{

    protected $table = "mpesa_webhook";

    public $migrationDependancy = [];

    protected $fillable = ['slug', 'confirmation_url', 'validation_url', 'paybill_till', 'shortcode', 'published'];

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
        $table->string('slug');
        $table->string('validation_url');
        $table->string('confirmation_url');
        $table->string('paybill_till');
        $table->string('shortcode');
        $table->tinyInteger('published')->default(false);
    }
}
