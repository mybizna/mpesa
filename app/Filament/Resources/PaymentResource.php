<?php

namespace Modules\Mpesa\Filament\Resources;

use Modules\Base\Filament\Resources\BaseResource;
use Modules\Mpesa\Models\Payment;

class PaymentResource extends BaseResource
{
    protected static ?string $model = Payment::class;

    protected static ?string $slug = 'mpesa/payment';

    protected static ?string $navigationGroup = 'Mpesa';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

}
