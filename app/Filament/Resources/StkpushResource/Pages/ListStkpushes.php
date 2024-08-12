<?php

namespace Modules\Mpesa\Filament\Resources\StkpushResource\Pages;

use Modules\Mpesa\Filament\Resources\StkpushResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStkpushes extends ListRecords
{
    protected static string $resource = StkpushResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
