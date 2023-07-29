<?php

/** @var \Modules\Base\Classes\Fetch\Menus $this */

$this->add_module_info("mpesa", [
    'title' => 'Mpesa',
    'description' => 'Mpesa',
    'icon' => 'fas fa-sack-dollar',
    'path' => '/mpesa/admin/payment',
    'class_str' => 'text-green border-green',
    'position' => 3,
]);

$this->add_menu("mpesa", "payment", "Payment", "/mpesa/admin/payment", "fas fa-cogs", 5);
$this->add_menu("mpesa", "stkpush", "STK Push", "/mpesa/admin/stkpush", "fas fa-cogs", 5);
$this->add_menu("mpesa", "setting", "Setting", "/mpesa/admin/gateway", "fas fa-cogs", 5);

$this->add_submenu("mpesa", "setting", "Gateway", "/mpesa/admin/gateway", 5);
$this->add_submenu("mpesa", "setting", "Webhook", "/mpesa/admin/webhook", 5);
$this->add_submenu("mpesa", "setting", "Simulate", "/mpesa/admin/simulate", 5);
