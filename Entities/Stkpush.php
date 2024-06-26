<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Stkpush extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_stkpush";

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
    protected $fillable = ['amount', 'phone', 'reference', 'description', 'command', 'gateway_id', 'completed', 'successful', 'merchant_request_id', 'checkout_request_id'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['reference'];

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
        $this->fields->string('amount')->html('text');
        $this->fields->string('phone')->html('text');
        $this->fields->string('reference')->html('textarea');
        $this->fields->string('description')->nullable()->html('textarea');
        $this->fields->string('command')->nullable()->html('text');
        $this->fields->string('merchant_request_id')->nullable()->html('text');
        $this->fields->string('checkout_request_id')->nullable()->html('text');
        $this->fields->integer('gateway_id')->nullable()->html('recordpicker')->relation(['mpesa', 'gateway']);
        $this->fields->tinyInteger('completed')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('successful')->nullable()->default(0)->html('switch');
    }

    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {

        $structure['table'] = ['amount', 'phone', 'reference', 'command', 'gateway_id', 'completed', 'successful', 'merchant_request_id', 'checkout_request_id'];
        $structure['form'] = [
            ['label' => 'Stkpush Phone', 'class' => 'col-span-full', 'fields' => ['phone']],
            ['label' => 'Stkpush Details', 'class' => 'col-span-full  md:col-span-6 md:pr-2', 'fields' => ['amount', 'reference', 'merchant_request_id', 'checkout_request_id']],
            ['label' => 'Stkpush Setting', 'class' => 'col-span-full  md:col-span-6 md:pr-2', 'fields' => ['gateway_id', 'completed', 'successful']],
            ['label' => 'Stkpush Description', 'class' => 'col-span-full', 'fields' => ['description']],
        ];
        $structure['filter'] = ['amount', 'phone', 'reference', 'command', 'gateway_id', 'completed', 'successful'];

        return $structure;
    }


    /**
     * Define rights for this model.
     *
     * @return array
     */
    public function rights(): array
    {
        $rights = parent::rights();

        $rights['staff'] = ['view' => true];
        $rights['registered'] = ['view' => true];
        $rights['guest'] = [];

        return $rights;
    }
}
