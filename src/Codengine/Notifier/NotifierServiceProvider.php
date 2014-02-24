<?php namespace Codengine\Notifier;

use Illuminate\Support\ServiceProvider;

class NotifierServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('codengine/notifier');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['notifier'] = $this->app->share(function($app){
            return new NotificationService(array(
                $app->make('Codengine\Notifier\Notifiers\EmailNotifier'),
                $app->make('Codengine\Notifier\Notifiers\SMSNotifier'),
            ));
        });
    }

    public function provides()
    {
        return array('notifier');
    }

}