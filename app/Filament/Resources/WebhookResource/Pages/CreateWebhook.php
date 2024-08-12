<?php

namespace Modules\Mpesa\Filament\Resources\WebhookResource\Pages;

use Modules\Mpesa\Filament\Resources\WebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWebhook extends CreateRecord
{
    protected static string $resource = WebhookResource::class;
}
