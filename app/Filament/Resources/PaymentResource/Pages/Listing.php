<?php

namespace Modules\Mpesa\Filament\Resources\PaymentResource\Pages;

use Modules\Base\Filament\Resources\Pages\ListingBase;
use Modules\Mpesa\Filament\Resources\PaymentResource;

// Class List that extends ListBase
class Listing extends ListingBase
{
    protected static string $resource = PaymentResource::class;
}
