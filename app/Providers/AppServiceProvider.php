<?php

namespace App\Providers;

use App\Http\Controllers\V1\Project\File\UploadProjectFileController;
use Core\Application\Account\Shared\Gateways\AccountCommandInterface;
use Core\Application\Account\Shared\Gateways\AccountMapperInterface;
use Core\Application\File\Shared\Gateways\FileCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectCommandInterface;
use Core\Application\Project\Shared\Gateways\ProjectFileMapperInterface;
use Core\Application\Project\Shared\Gateways\ProjectMapperInterface;
use Core\Application\User\Shared\Gateways\UserCommandInterface;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Services\Framework\Contracts\AuthContract;
use Core\Services\Framework\Contracts\StrContract;
use Core\Services\Framework\Contracts\TransactionManagerContract;
use Core\Services\Framework\Contracts\UuidContract;
use Core\Services\Framework\FrameworkContract;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Infra\Database\Account\Command\AccountCommand;
use Infra\Database\Account\Mapper\AccountMapper;
use Infra\Database\File\Command\FileProjectCommand;
use Infra\Database\Project\Command\ProjectCommand;
use Infra\Database\Project\Mapper\ProjectFileMapper;
use Infra\Database\Project\Mapper\ProjectMapper;
use Infra\Database\User\Command\UserCommand;
use Infra\Database\User\Mapper\UserMapper;
use Infra\Services\Framework\Adapters\AuthAdapter;
use Infra\Services\Framework\Adapters\StrAdapter;
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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

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
        $this->app->bind(StrContract::class, StrAdapter::class);

        $this->app->bind(UserCommandInterface::class, UserCommand::class);
        $this->app->bind(UserMapperInterface::class, UserMapper::class);

        $this->app->bind(AccountCommandInterface::class, AccountCommand::class);
        $this->app->bind(AccountMapperInterface::class, AccountMapper::class);

        $this->app->bind(ProjectCommandInterface::class, ProjectCommand::class);
        $this->app->bind(ProjectMapperInterface::class, ProjectMapper::class);

        $this->app->bind(ProjectFileMapperInterface::class, ProjectFileMapper::class);

        $this->app->when([UploadProjectFileController::class])
            ->needs(FileCommandInterface::class)
            ->give(FileProjectCommand::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            /**
             * lembrar de validar o ip para não bloquear o acesso de todos os usuários
             * devido ao nginx ou load balancer passar o ip do servidor
             */
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}