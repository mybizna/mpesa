<?php

namespace Modules\Mpesa\Filament\Resources\SimulateResource\Pages;

use Modules\Mpesa\Filament\Resources\SimulateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimulate extends EditRecord
{
    protected static string $resource = SimulateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
