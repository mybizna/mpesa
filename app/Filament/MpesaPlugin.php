<?php

namespace Modules\Mpesa\Filament;

use Coolsam\Modules\Concerns\ModuleFilamentPlugin;
use Filament\Contracts\Plugin;
use Filament\Panel;

class MpesaPlugin implements Plugin
{
    use ModuleFilamentPlugin;

    public function getModuleName(): string
    {
        return 'Mpesa';
    }

    public function getId(): string
    {
        return 'mpesa';
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }
}
