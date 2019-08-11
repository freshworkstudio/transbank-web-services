<?php

namespace Freshwork\Transbank\Laravel;

use Freshwork\Transbank\CertificationBagFactory;
use Freshwork\Transbank\TransbankServiceFactory;
use Freshwork\Transbank\WebpayCaptureNullify;
use Freshwork\Transbank\WebpayDeferred;
use Freshwork\Transbank\WebpayNormal;
use Freshwork\Transbank\WebpayOneClick;
use Freshwork\Transbank\WebpayPatPass;
use Illuminate\Support\ServiceProvider;

class WebpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/webpay.php' => config_path('webpay.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/certs/.gitignore' => storage_path('app/certs/.gitignore'),
        ], 'certs');
    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/config/webpay.php', 'webpay'
        );

        /*
        |--------------------------------------------------------------------------
        | Webpay Normal
        |--------------------------------------------------------------------------
        */
        $this->app->singleton(WebpayNormal::class, function ($app) {
            if ($this->app->environment('local')) {
                return TransbankServiceFactory::normal(CertificationBagFactory::integrationWebpayNormal());
            }
            return TransbankServiceFactory::normal(
                CertificationBagFactory::production(
                    config('webpay.normal.client_private_key'),
                    config('webpay.normal.client_certificate'),
                    config('webpay.normal.transbank_certificate')
                )
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Webpay Patpass
        |--------------------------------------------------------------------------
        */
        $this->app->singleton(WebpayPatPass::class, function ($app) {
            if ($this->app->environment('local')) {
                return TransbankServiceFactory::patpass(CertificationBagFactory::integrationPatPass());
            }
            return TransbankServiceFactory::patpass(
                CertificationBagFactory::production(
                    config('webpay.patpass.client_private_key'),
                    config('webpay.patpass.client_certificate'),
                    config('webpay.patpass.transbank_certificate')
                )
            );
        });

        $this->app->singleton(WebpayOneClick::class, function ($app) {
            if ($this->app->environment('local')) {
                return TransbankServiceFactory::oneclick(CertificationBagFactory::integrationOneClick());
            }
            return TransbankServiceFactory::oneclick(
                CertificationBagFactory::production(
                    config('webpay.oneclick.client_private_key'),
                    config('webpay.oneclick.client_certificate'),
                    config('webpay.oneclick.transbank_certificate')
                )
            );
        });

        $this->app->singleton(WebpayDeferred::class, function ($app) {
            if ($this->app->environment('local')) {
                return TransbankServiceFactory::deferred(CertificationBagFactory::integrationWebpayDeferred());
            }
            return TransbankServiceFactory::deferred(
                CertificationBagFactory::production(
                    config('webpay.deferred.client_private_key'),
                    config('webpay.deferred.client_certificate'),
                    config('webpay.deferred.transbank_certificate')
                )
            );
        });

        $this->app->singleton(WebpayCaptureNullify::class, function ($app) {
            if ($this->app->environment('local')) {
                return TransbankServiceFactory::captureNullify(CertificationBagFactory::integrationWebpayDeferred());
            }
            return TransbankServiceFactory::captureNullify(
                CertificationBagFactory::production(
                    config('webpay.deferred.client_private_key'),
                    config('webpay.deferred.client_certificate'),
                    config('webpay.deferred.transbank_certificate')
                )
            );
        });
    }
}
