<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;
use Modules\Core\Classes\Views\FormBuilder;
use Modules\Core\Classes\Views\ListTable;

class Gateway extends BaseModel
{

    protected $table = "mpesa_gateway";

    public $migrationDependancy = [];

    protected $fillable = ['title', 'slug', 'ledger_id', 'currency_id', 'consumer_key',
        'consumer_secret', 'initiator_name', 'initiator_password', 'party_a', 'party_b', 'type',
        'passkey', 'business_shortcode', 'phone_number', 'method',
        'description', 'default', 'sandbox', 'published'];

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

        $fields->name('title')->type('text')->ordering(true);
        $fields->name('business_shortcode')->type('text')->label('ShortCode')->ordering(true);
        $fields->name('slug')->type('text')->ordering(true);
        $fields->name('ledger_id')->type('recordpicker')->table('account_ledger')->ordering(true);
        $fields->name('currency_id')->type('recordpicker')->table('core_currency')->ordering(true);
        $fields->name('consumer_key')->type('text')->ordering(true);
        $fields->name('consumer_secret')->type('text')->ordering(true);
        $fields->name('default')->type('switch')->ordering(true);
        $fields->name('sandbox')->type('switch')->ordering(true);
        $fields->name('published')->type('switch')->ordering(true);

        return $fields;

    }

    public function formBuilder()
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('title')->type('text')->group('w-1/2');
        $fields->name('slug')->type('text')->group('w-1/2');
        $fields->name('ledger_id')->type('recordpicker')->table('account_ledger')->group('w-1/2');
        $fields->name('currency_id')->type('recordpicker')->table('core_currency')->group('w-1/2');
        $fields->name('consumer_key')->type('text')->group('w-1/2');
        $fields->name('consumer_secret')->type('text')->group('w-1/2');
        $fields->name('default')->type('switch')->group('w-1/2');
        $fields->name('sandbox')->type('switch')->group('w-1/2');
        $fields->name('initiator_name')->type('text')->group('w-1/2');
        $fields->name('initiator_password')->type('text')->group('w-1/2');
        $fields->name('party_a')->type('text')->group('w-1/2');
        $fields->name('party_b')->type('text')->group('w-1/2');
        $fields->name('type')->type('select')->options(['paybill' => 'Paybill', 'tillno' => 'Till No'])->group('w-1/2');
        $fields->name('passkey')->type('text')->group('w-1/2');
        $fields->name('business_shortcode')->type('text')->label('ShortCode')->group('w-1/2');
        $fields->name('phone_number')->type('text')->group('w-1/2');
        $fields->name('method')->type('select')->options(['sending' => 'Sending', 'stkpush' => 'STK Push'])->group('w-1/2');
        $fields->name('description')->type('textarea')->group('w-full');

        return $fields;

    }

    public function filter()
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('title')->type('switch')->group('w-1/6');
        $fields->name('ledger_id')->type('recordpicker')->group('w-1/6');
        $fields->name('currency_id')->type('recordpicker')->group('w-1/6');
        $fields->name('default')->type('switch')->group('w-1/6');
        $fields->name('sandbox')->type('switch')->group('w-1/6');

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
