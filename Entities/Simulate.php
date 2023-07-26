<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Classes\Migration;
use Modules\Base\Entities\BaseModel;
use Modules\Core\Classes\Views\FormBuilder;
use Modules\Core\Classes\Views\ListTable;

class Simulate extends BaseModel
{

    protected $table = "mpesa_simulate";

    public $migrationDependancy = [];

    protected $fillable = ['amount', 'phone', 'reference', 'description', 'gateway_id', 'completed', 'successful'];

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

        $fields->name('amount')->type('text')->ordering(true);
        $fields->name('phone')->type('text')->ordering(true);
        $fields->name('gateway_id')->type('recordpicker')->table('mpesa_gateway')->ordering(true);
        $fields->name('completed')->type('switch')->ordering(true);
        $fields->name('successful')->type('switch')->ordering(true);

        return $fields;

    }

    public function formBuilder()
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('amount')->type('text')->group('w-1/2');
        $fields->name('phone')->type('text')->group('w-1/2');
        $fields->name('reference')->type('text')->group('w-1/2');
        $fields->name('description')->type('text')->group('w-1/2');
        $fields->name('gateway_id')->type('recordpicker')->table('mpesa_gateway')->group('w-1/2');
        $fields->name('completed')->type('switch')->group('w-1/2');
        $fields->name('successful')->type('switch')->group('w-1/2');

        return $fields;

    }

    public function filter()
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('amount')->type('text')->group('w-1/6');
        $fields->name('phone')->type('text')->group('w-1/6');
        $fields->name('gateway_id')->type('recordpicker')->table('mpesa_gateway')->group('w-1/6');
        $fields->name('completed')->type('switch')->group('w-1/6');
        $fields->name('successful')->type('switch')->group('w-1/6');

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
        $table->string('amount');
        $table->string('phone');
        $table->string('reference')->nullable();
        $table->string('description')->nullable();
        $table->integer('gateway_id')->nullable();
        $table->tinyInteger('completed')->nullable()->default(0);
        $table->tinyInteger('successful')->nullable()->default(0);
    }

    public function post_migration(Blueprint $table)
    {
        Migration::addForeign($table, 'mpesa_gateway', 'gateway_id');
    }
}
