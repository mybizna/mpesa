<?php

namespace Modules\Mpesa\Entities;

use Modules\Base\Entities\BaseModel;
use Illuminate\Database\Schema\Blueprint;

class Gateway extends BaseModel
{

    protected $table = "mpesa_gateway";

    public $migrationDependancy = [];

    protected $fillable = ['title',  'slug', 'ledger_id', 'currency_id', 'consumer_key',  
    'consumer_secret', 'initiator_name','initiator_password','party_a','party_b', 'type', 
     'passkey', 'business_shortcode', 'phone_number','method',
    'description', 'default', 'sandbox', 'published'];


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
        $table->string('title');
        $table->string('slug');
        $table->string('consumer_key');
        $table->string('consumer_secret');
        $table->string('initiator_name');
        $table->string('initiator_password');
        $table->string('passkey');
        $table->string('party_a');
        $table->string('party_b');
        $table->string('business_shortcode');
        $table->string('phone_number')->nullable();
        $table->enum('type', ['paybill', 'tillno'])->default('paybill')->nullable();
        $table->enum('method', ['sending', 'stkpush'])->default('sending')->nullable();
        $table->integer('ledger_id')->nullable();
        $table->integer('currency_id')->nullable();
        $table->string('description')->nullable();
        $table->tinyInteger('default')->nullable()->default(0);
        $table->tinyInteger('sandbox')->nullable()->default(0);
        $table->tinyInteger('published')->nullable()->default(0);
    }
}

/**
 * 
 * ReceiverIdentifierType
 * 
 */