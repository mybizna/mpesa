<?php

namespace Modules\Mpesa\Filament\Resources\SimulateResource\Pages;

use Modules\Mpesa\Filament\Resources\SimulateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSimulates extends ListRecords
{
    protected static string $resource = SimulateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
