<?php

namespace App\Providers;

use App\Interfaces\EmployeeInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EmployeeRepository;
use App\Interfaces\ProgrammingLanguageInterface;
use App\Interfaces\ProjectAssignmentInterface;
use App\Interfaces\ProjectInterface;
use App\Repositories\ProgrammingLanguageRepository;
use App\Repositories\ProjectAssignmentRepository;
use App\Repositories\ProjectRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmployeeInterface::class, EmployeeRepository::class);
        $this->app->bind(ProjectAssignmentInterface::class, ProjectAssignmentRepository::class);
        $this->app->bind(ProjectInterface::class, ProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
