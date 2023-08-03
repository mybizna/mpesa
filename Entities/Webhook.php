<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Classes\Views\FormBuilder;
use Modules\Base\Classes\Views\ListTable;
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
     * The attributes that should be mutated to dates.
     *
     * @var array <string>
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Function for defining list of fields in table view.
     *
     * @return ListTable
     */
    public function listTable(): ListTable
    {
        // listing view fields
        $fields = new ListTable();

        $fields->name('slug')->type('text')->ordering(true);
        $fields->name('validation_url')->type('text')->ordering(true);
        $fields->name('confirmation_url')->type('text')->ordering(true);
        $fields->name('paybill_till')->type('text')->ordering(true);
        $fields->name('shortcode')->type('text')->ordering(true);
        $fields->name('published')->type('switch')->ordering(true);

        return $fields;

    }

    /**
     * Function for defining list of fields in form view.
     * 
     * @return FormBuilder
     */
    public function formBuilder(): FormBuilder
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('slug')->type('text')->group('w-1/2');
        $fields->name('validation_url')->type('text')->group('w-1/2');
        $fields->name('confirmation_url')->type('text')->group('w-1/2');
        $fields->name('paybill_till')->type('text')->group('w-1/2');
        $fields->name('shortcode')->type('text')->group('w-1/2');
        $fields->name('published')->type('switch')->group('w-1/2');

        return $fields;

    }

    /**
     * Function for defining list of fields in filter view.
     * 
     * @return FormBuilder
     */
    public function filter(): FormBuilder
    {
        // listing view fields
        $fields = new FormBuilder();

        $fields->name('slug')->type('text')->group('w-1/6');
        $fields->name('validation_url')->type('text')->group('w-1/6');
        $fields->name('confirmation_url')->type('text')->group('w-1/6');
        $fields->name('paybill_till')->type('text')->group('w-1/6');
        $fields->name('shortcode')->type('text')->group('w-1/6');
        $fields->name('published')->type('switch')->group('w-1/6');

        return $fields;

    }
    /**
     * List of fields to be migrated to the datebase when creating or updating model during migration.
     *
     * @param Blueprint $table
     * @return void
     */
    public function migration(Blueprint $table): void
    {
        $table->increments('id');
        $table->string('slug');
        $table->string('validation_url');
        $table->string('confirmation_url');
        $table->string('paybill_till');
        $table->string('shortcode');
        $table->tinyInteger('published')->nullable()->default(0);
    }
}
