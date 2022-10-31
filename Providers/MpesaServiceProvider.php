<?php

namespace Modules\Mpesa\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\Core\Entities\Setting;

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
        // TODO: Rework using setting

        $DS = DIRECTORY_SEPARATOR;
        $modules_path = realpath(base_path()) . $DS . 'Modules' . $DS . 'Mpesa';

        if (is_dir($modules_path)) {

            $merged_settings = [];
            $config_path = $modules_path . $DS . 'settings.php';

            if (file_exists($config_path)) {
                $settings = require $config_path;

                foreach ($settings as $key => $setting) {

                    $value = $setting['value'];
                    $db_setting = Setting::where(['module' => 'mpesa', 'name' => $key])->first();

                    if ($db_setting) {
                        $value = $db_setting->value;
                    }

                    $merged_settings[$key] = $value;

                }

            }

            //print_r($merged_settings); exit;

            $return_url_t = Str::of($merged_settings['return_url'])->rtrim('/');

            $static_settings = [
                'accounts' => [
                    'staging' => [
                        'sandbox' => true,
                        'key' => $merged_settings['sandbox_customer_key'],
                        'secret' => $merged_settings['sandbox_customer_secret'],
                        'initiator' => $merged_settings['sandbox_initiator'],
                        'id_validation_callback' => $return_url_t . '/callback',
                        'lnmo' => [
                            'paybill' => $merged_settings['sandbox_paybill_tillno'],
                            'shortcode' => $merged_settings['sandbox_short_code'],
                            'passkey' => $merged_settings['sandbox_pass_key'],
                            'callback' => $return_url_t . '/callback',
                        ],
                    ],

                    'paybill_1' => [
                        'sandbox' => false,
                        'key' => $merged_settings['customer_key'],
                        'secret' => $merged_settings['customer_secret'],
                        'initiator' => $merged_settings['initiator'],
                        'id_validation_callback' => $return_url_t . '/callback',
                        'lnmo' => [
                            'paybill' => $merged_settings['paybill_tillno'],
                            'shortcode' => $merged_settings['short_code'],
                            'passkey' => $merged_settings['pass_key'],
                            'callback' => $return_url_t . '/callback',
                        ],
                    ],
                ],
            ];

            $config = $this->app['config']->get('mpesa', []);
            $this->app['config']->set('mpesa', array_merge($static_settings, $config));

        }

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
