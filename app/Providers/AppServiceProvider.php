<?php

namespace App\Providers;

use Core\Application\Account\Commons\Gateways\AccountCommandInterface;
use Core\Application\Account\Commons\Gateways\AccountRepositoryInterface;
use Core\Application\User\Commons\Gateways\UserCommandInterface;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Services\Framework\Contracts\AuthContract;
use Core\Services\Framework\Contracts\TransactionManagerContract;
use Core\Services\Framework\Contracts\UuidContract;
use Core\Services\Framework\FrameworkContract;
use Illuminate\Support\ServiceProvider;
use Infra\Database\Account\Command\AccountCommand;
use Infra\Database\Account\Repository\AccountRepository;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Repository\UserRepository;
use Infra\Services\Framework\Adapters\AuthAdapter;
use Infra\Services\Framework\Adapters\TransactionManagerAdapter;
use Infra\Services\Framework\Adapters\UuidAdapter;
use Infra\Services\Framework\FrameworkService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerDataBaseBinds();
    }

    /**
     * @return void
     */
    private function registerDataBaseBinds(): void
    {
        $this->app->bind(FrameworkContract::class, FrameworkService::class);
        $this->app->bind(TransactionManagerContract::class, TransactionManagerAdapter::class);
        $this->app->bind(AuthContract::class, AuthAdapter::class);
        $this->app->bind(UuidContract::class, UuidAdapter::class);

        $this->app->bind(UserCommandInterface::class, UserCommand::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->bind(AccountCommandInterface::class, AccountCommand::class);
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
    }
}