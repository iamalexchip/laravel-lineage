<?php

namespace Zerochip;

use Zerochip\Lineage;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class LineageServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Collection macro: Lineage
         *
         * @param string $lineage
         * @return mixed
         */
        Collection::macro('lineage', function ($lineage) {
            return Lineage::get($this, $lineage);
        });
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
