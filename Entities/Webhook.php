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
    protected $fillable = ['slug', 'confirmation_url', 'validation_url', 'paybill_till', 'shortcode', 'published'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['slug'];

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

    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {
        $structure['table'] = ['slug', 'confirmation_url', 'validation_url', 'paybill_till', 'shortcode', 'published'];
        $structure['form'] = [
            ['label' => 'Wbehook Slug', 'class' => 'col-span-full', 'fields' => ['slug']],
            ['label' => 'Webhook Detail', 'class' => 'col-span-full  md:col-span-6 md:pr-2', 'fields' => ['paybill_till', 'shortcode', 'published']],
            ['label' => 'Webhook Url', 'class' => 'col-span-full md:col-span-6ull  md:col-span-6 md:pr-2', 'fields' => ['confirmation_url', 'validation_url']],
        ];
        $structure['filter'] = ['slug', 'confirmation_url', 'validation_url', 'paybill_till', 'published'];

        return $structure;
    }


    /**
     * Define rights for this model.
     *
     * @return array
     */
    public function rights(): array
    {

    }
}
