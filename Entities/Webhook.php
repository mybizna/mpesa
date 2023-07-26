<?php

namespace Modules\Mpesa\Entities;

use Illuminate\Database\Schema\Blueprint;
use Modules\Base\Entities\BaseModel;
use Modules\Core\Classes\Views\FormBuilder;
use Modules\Core\Classes\Views\ListTable;

class Webhook extends BaseModel
{

    protected $table = "mpesa_webhook";

    public $migrationDependancy = [];

    protected $fillable = ['slug', 'confirmation_url', 'validation_url', 'paybill_till', 'shortcode', 'published'];

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

        $fields->name('slug')->type('text')->ordering(true);
        $fields->name('validation_url')->type('text')->ordering(true);
        $fields->name('confirmation_url')->type('text')->ordering(true);
        $fields->name('paybill_till')->type('text')->ordering(true);
        $fields->name('shortcode')->type('text')->ordering(true);
        $fields->name('published')->type('switch')->ordering(true);

        return $fields;

    }

    public function formBuilder()
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

    public function filter()
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
     * List of fields for managing postings.
     *
     * @param Blueprint $table
     * @return void
     */
    public function migration(Blueprint $table)
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
