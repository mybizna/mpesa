<?php

namespace Modules\Mpesa\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Mpesa\Models\Gateway;

class GatewayResource extends BaseResource
{
    protected static ?string $model = Gateway::class;

    protected static ?string $slug = 'mpesa/gateway';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


}
