<?php

use App\Providers\AppServiceProvider;
use Modules\Dashboard\Providers\DashboardServiceProvider;
use Modules\Store\Providers\StoreServiceProvider;

return [
    AppServiceProvider::class,
    DashboardServiceProvider::class,
    StoreServiceProvider::class,
];
