<?php

namespace App\Providers;

use App\Interfaces\EloquentRepositoryInterface;
use App\Interfaces\ApplicationRepositoryInterface;

use App\Interfaces\HealthLogRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\WebhookRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\ApplicationRepository;

use App\Repositories\HealthLogRepository;
use App\Repositories\UserRepository;
use App\Repositories\WebhookRepository;
use Illuminate\Support\ServiceProvider;


/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(HealthLogRepositoryInterface::class, HealthLogRepository::class);
        $this->app->bind(WebhookRepositoryInterface::class, WebhookRepository::class);
    }
}
