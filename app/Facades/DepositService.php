<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DepositService extends Facade {
    protected static function getFacadeAccessor() {
        return 'deposit';
    }
}
