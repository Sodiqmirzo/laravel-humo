<?php

namespace Uzbek\Humo\Facades;

use Illuminate\Support\Facades\Facade;

class Humo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'humo';
    }
}
