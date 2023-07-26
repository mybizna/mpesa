<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;
use Modules\Core\Classes\Views\FormBuilder;
use Modules\Core\Classes\Views\ListTable;

class Payment extends BaseModel
{

    protected $table = "mpesa_payment";

    public $migrationDependancy = [];

    protected $fillable = ['trans_type', 'trans_id', 'trans_time', 'trans_amount', 'business_short_code', 'bill_ref_number', 'invoice_number', 'org_account', 'third_party_id', 'msisdn', 'first_name', 'middle_name', 'last_name', 'completed', 'successful', 'published'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function listTable()
    {
        // listing view fields
        $fields = new ListTable();

        $fields->name('trans_type')->type('text')->ordering(true);
        $fields->name('trans_id')->type('text')->ordering(true);
        $fields->name('first_name')->type('text')->ordering(true);
        $fields->name('middle_name')->type('text')->ordering(true);
        $fields->name('last_name')->type('text')->ordering(true);
        $fields->name('trans_time')->type('text')->ordering(true);
        $fields->name('trans_amount')->type('text')->ordering(true);
        $fields->name('business_short_code')->type('text')->ordering(true);
        $fields->name('bill_ref_number')->type('text')->ordering(true);
        $fields->name('invoice_number')->type('text')->ordering(true);
        $fields->name('org_account')->type('text')->ordering(true);
        $fields->name('third_party_id')->type('text')->ordering(true);
        $fields->name('msisdn')->type('text')->ordering(true);
        $fields->name('completed')->type('switch')->ordering(true);
        $fields->name('successful')->type('switch')->ordering(true);
        $fields->name('published')->type('switch')->ordering(true);

        return $fields;

    }

    public function formBuilder()
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('trans_type')->type('text')->group('w-1/2');
        $fields->name('trans_id')->type('text')->group('w-1/2');
        $fields->name('trans_time')->type('text')->group('w-1/2');
        $fields->name('trans_amount')->type('text')->group('w-1/2');
        $fields->name('business_short_code')->type('text')->group('w-1/2');
        $fields->name('bill_ref_number')->type('text')->group('w-1/2');
        $fields->name('invoice_number')->type('text')->group('w-1/2');
        $fields->name('org_account')->type('text')->group('w-1/2');
        $fields->name('third_party_id')->type('text')->group('w-1/2');
        $fields->name('msisdn')->type('text')->group('w-1/2');
        $fields->name('first_name')->type('text')->group('w-1/2');
        $fields->name('middle_name')->type('text')->group('w-1/2');
        $fields->name('last_name')->type('text')->group('w-1/2');
        $fields->name('completed')->type('switch')->group('w-1/2');
        $fields->name('successful')->type('switch')->group('w-1/2');
        $fields->name('published')->type('switch')->group('w-1/2');

        // $fields->name(

        return $fields;

    }

    public function filter()
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('trans_type')->type('text')->group('w-1/6');
        $fields->name('trans_id')->type('text')->group('w-1/6');
        $fields->name('msisdn')->type('text')->group('w-1/6');
        $fields->name('first_name')->type('text')->group('w-1/6');
        $fields->name('middle_name')->type('text')->group('w-1/6');
        $fields->name('last_name')->type('text')->group('w-1/6');
        $fields->name('completed')->type('switch')->group('w-1/6');
        $fields->name('successful')->type('switch')->group('w-1/6');
        $fields->name('published')->type('switch')->group('w-1/6');

        return $fields;

    }

    /**
     * List of fields for managing postings.
     *
     * @param Blueprint $table
     * @return void
     */
    public function migration(Blueprint $table)
    {
        $table->increments('id');
        $table->string('trans_type')->nullable();
        $table->string('trans_id')->nullable();
        $table->string('trans_time')->nullable();
        $table->string('trans_amount')->nullable();
        $table->string('business_short_code')->nullable();
        $table->string('bill_ref_number')->nullable();
        $table->string('invoice_number')->nullable();
        $table->string('org_account')->nullable();
        $table->string('third_party_id')->nullable();
        $table->string('msisdn')->nullable();
        $table->string('first_name')->nullable();
        $table->string('middle_name')->nullable();
        $table->string('last_name')->nullable();
        $table->tinyInteger('published')->nullable()->default(0);
        $table->tinyInteger('completed')->nullable()->default(0);
        $table->tinyInteger('successful')->nullable()->default(0);
    }
}
