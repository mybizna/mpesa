<?php

namespace Modules\Mpesa\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Mpesa\Models\Simulate;

class SimulateResource extends BaseResource
{
    protected static ?string $model = Simulate::class;

    protected static ?string $slug = 'mpesa/simulate';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


}
