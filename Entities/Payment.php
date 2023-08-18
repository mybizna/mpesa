<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;

class Payment extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_payment";

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
    protected $fillable = ['trans_type', 'trans_id', 'trans_time', 'trans_amount', 'business_short_code', 'bill_ref_number', 'invoice_number', 'org_account', 'third_party_id', 'msisdn', 'first_name', 'middle_name', 'last_name', 'completed', 'successful', 'published'];

    /**
     * The fields that are to be render when performing relationship queries.
     *
     * @var array<string>
     */
    public $rec_names = ['trans_type', 'msisdn', 'trans_amount'];

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

        $this->fields->increments('id')->html('text');
        $this->fields->string('trans_type')->nullable()->html('text');
        $this->fields->string('trans_id')->nullable()->html('text');
        $this->fields->string('trans_time')->nullable()->html('text');
        $this->fields->string('trans_amount')->nullable()->html('text');
        $this->fields->string('business_short_code')->nullable()->html('text');
        $this->fields->string('bill_ref_number')->nullable()->html('text');
        $this->fields->string('invoice_number')->nullable()->html('text');
        $this->fields->string('org_account')->nullable()->html('text');
        $this->fields->string('third_party_id')->nullable()->html('text');
        $this->fields->string('msisdn')->nullable()->html('text');
        $this->fields->string('first_name')->nullable()->html('text');
        $this->fields->string('middle_name')->nullable()->html('text');
        $this->fields->string('last_name')->nullable()->html('text');
        $this->fields->tinyInteger('published')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('completed')->nullable()->default(0)->html('switch');
        $this->fields->tinyInteger('successful')->nullable()->default(0)->html('switch');
    }

    /**
     * List of structure for this model.
     */
    public function structure($structure): array
    {
        $structure['table'] = ['first_name', 'middle_name', 'last_name', 'msisdn', 'trans_type', 'trans_id', 'trans_time', 'trans_amount', 'business_short_code', 'bill_ref_number', 'invoice_number', 'completed', 'successful', 'published'];
        $structure['filter'] = ['msisdn', 'trans_amount', 'business_short_code', 'bill_ref_number', 'invoice_number', 'completed', 'successful', 'published'];

        return $structure;
    }
}
