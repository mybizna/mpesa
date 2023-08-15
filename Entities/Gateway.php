<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Gateway extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_gateway";

    /**
     * List of tables names that are need in this model.
     *
     * @var array<string>
     */
    public array $migrationDependancy = [];

    /**
     * The fields that can be filled
     *
     * @var array<string>
     */
    protected $fillable = ['title', 'slug', 'ledger_id', 'currency_id', 'consumer_key',
        'consumer_secret', 'initiator_name', 'initiator_password', 'party_a', 'party_b', 'type',
        'passkey', 'business_shortcode', 'phone_number', 'method',
        'description', 'default', 'sandbox', 'published'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['title'];

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

        $type = ['paybill', 'tillno'];
        $method =  ['sending', 'stkpush'];

        $this->fields->increments('id')->html('text');
        $this->fields->string('title')->html('text');
        $this->fields->string('slug')->html('text');
        $this->fields->string('consumer_key')->html('text');
        $this->fields->string('consumer_secret')->html('text');
        $this->fields->string('initiator_name')->html('text');
        $this->fields->string('initiator_password')->html('text');
        $this->fields->string('passkey')->html('text');
        $this->fields->string('party_a')->html('text');
        $this->fields->string('party_b')->html('text');
        $this->fields->string('business_shortcode')->html('text');
        $this->fields->string('phone_number')->nullable()->html('text');
        $this->fields->enum('type', $type)->options($type)->default('paybill')->nullable()->html('select');
        $this->fields->enum('method',$method)->options($method)->default('sending')->nullable()->html('select');
        $this->fields->integer('ledger_id')->nullable()->html('recordpicker')->relation(['account', 'ledger']);
        $this->fields->integer('currency_id')->nullable()->html('recordpicker')->relation(['core', 'currency']);
        $this->fields->string('description')->nullable()->html('textarea');
        $this->fields->tinyInteger('default')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('sandbox')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('published')->nullable()->default(0)->html('switch');
    }

    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {
        $structure = [
            'table' => ['title', 'ledger_id', 'currency_id', 'consumer_key', 'consumer_secret', 'initiator_name', 'passkey', 'business_shortcode', 'phone_number', 'method', 'published'],
            'filter' => ['ledger_id', 'currency_id'],
        ];

        return $structure;
    }

}

/**
 *
 * ReceiverIdentifierType
 *
 */
