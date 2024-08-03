<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Webhook extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_webhook";

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['slug', 'confirmation_url', 'validation_url', 'paybill_till', 'shortcode', 'published'];

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
        $this->fields->string('slug')->html('text');
        $this->fields->string('validation_url')->html('text');
        $this->fields->string('confirmation_url')->html('text');
        $this->fields->string('paybill_till')->html('text');
        $this->fields->string('shortcode')->html('text');
        $this->fields->tinyInteger('published')->nullable()->default(0)->html('switch');
    }



  
}
