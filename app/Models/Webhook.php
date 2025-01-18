<?php

namespace Modules\Mpesa\Models;

use Modules\Base\Models\BaseModel;
use Illuminate\Database\Schema\Blueprint;

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


    public function migration(Blueprint $table): void
    {

        $table->string('slug');
        $table->string('validation_url');
        $table->string('confirmation_url');
        $table->string('paybill_till');
        $table->string('shortcode');
        $table->tinyInteger('published')->nullable()->default(0);

    }
}
