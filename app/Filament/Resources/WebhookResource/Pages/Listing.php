<?php

namespace Modules\Mpesa\Filament\Resources\WebhookResource\Pages;

use Modules\Base\Filament\Resources\Pages\ListingBase;
use Modules\Mpesa\Filament\Resources\WebhookResource;

// Class List that extends ListBase
class Listing extends ListingBase
{
    protected static string $resource = WebhookResource::class;
}
