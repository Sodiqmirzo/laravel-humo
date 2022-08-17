<?php

namespace Uzbek\Humo;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HumoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('humo-svgate')->hasConfigFile();

        $this->app->singleton(Humo::class, function () {
            return new Humo();
        });
    }
}
