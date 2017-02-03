<?php 

namespace JohannesSchobel\InviteMe;

use Illuminate\Support\ServiceProvider;

class InviteMeServiceProvider extends ServiceProvider
{
    public function boot() {
        $this->publishes([
            __DIR__.'/../../config/inviteme.php'   => config_path('inviteme.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../migrations/' => base_path('/database/migrations'),
        ], 'migrations');
    }

    public function register() {
        $this->setupConfig();
    }

    /**
     * Get the Configuration
     */
    private function setupConfig() {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/inviteme.php'), 'inviteme');
    }
}