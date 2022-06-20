<?php

declare(strict_types=1);

namespace Coddin\DataTables;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

final class DataTablesServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
