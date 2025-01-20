<?php

namespace Modules\Mpesa\Models;

use Modules\Account\Models\Ledger;
use Modules\Base\Models\BaseModel;
use Modules\Core\Models\Currency;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gateway extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "mpesa_gateway";

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
     * The attributes that should be mutated to dates.
     *
     * @var array <string>
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * Add relationship to Ledger
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ledger(): BelongsTo
    {
        return $this->belongsTo(Ledger::class);
    }

    /**
     * Add relationship to Currency
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }


    public function migration(Blueprint $table): void
    {

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
        $table->enum('type', ['paybill', 'till_number', 'shortcode'])->default('paybill')->nullable();
        $table->enum('method', ['sending', 'receiving'])->default('sending')->nullable();
        $table->unsignedBigInteger('ledger_id')->nullable();
        $table->unsignedBigInteger('currency_id')->nullable();
        $table->string('description')->nullable();
        $table->tinyInteger('default')->nullable()->default(0);
        $table->tinyInteger('sandbox')->nullable()->default(0);
        $table->tinyInteger('published')->nullable()->default(0);

    }

    public function post_migration(Blueprint $table): void
    {
        $table->foreign('ledger_id')->references('id')->on('account_ledger')->onDelete('set null');
        $table->foreign('currency_id')->references('id')->on('core_currency')->onDelete('set null');
    }
}

