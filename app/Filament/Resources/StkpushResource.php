<?php

namespace Modules\Mpesa\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Mpesa\Models\Stkpush;

class StkpushResource extends BaseResource
{
    protected static ?string $model = Stkpush::class;

    protected static ?string $slug = 'mpesa/stkpush';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

}
