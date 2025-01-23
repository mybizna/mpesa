<?php

namespace Modules\Mpesa\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Mpesa\Models\Webhook;

class WebhookResource extends BaseResource
{
    protected static ?string $model = Webhook::class;

    protected static ?string $slug = 'mpesa/webhook';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

}
