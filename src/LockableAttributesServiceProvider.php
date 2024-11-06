<?php

namespace Sajtiii\LockableAttributes;

use Illuminate\Support\ServiceProvider;

class LockableAttributesServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'lockable-attributes');
    }
}
