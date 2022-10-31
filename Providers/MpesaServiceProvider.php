<?php

namespace Modules\Mpesa\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\Core\Entities\Setting;
use Modules\Mpesa\Entities\Gateway;

class MpesaServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Mpesa';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'mpesa';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {

        $db_setting = Setting::where(['module' => 'mpesa', 'name' => 'return_url'])->first();
        $gateways = Gateway::where(['published' => true])->get();

        $return_url = Str::of($db_setting->value)->rtrim('/');

        $static_settings = ['accounts' => []];

        foreach ($gateways as $key => $gateway) {
            $static_settings['accounts'][$gateway->slug] = [
                'sandbox' => $gateway->slug,
                'key' => $gateway->key,
                'secret' => $gateway->secret,
                'initiator' => $gateway->initiator,
                'id_validation_callback' => $return_url . '/callback',
                'lnmo' => [
                    'paybill' => $gateway->till_bill_no,
                    'shortcode' => $gateway->shortcode,
                    'passkey' => $gateway->passkey,
                    'callback' => $return_url . '/callback',
                ],
            ];
        }

        $config = $this->app['config']->get('mpesa', []);
        $this->app['config']->set('mpesa', array_merge($static_settings, $config));

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
