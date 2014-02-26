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
        $this->package('codengine/notifier', 'codengine/notifier');
    }

    private function initServices(array $services)
    {
        return array_filter($services, function(&$service) use ($services) {
            if($service['enabled'])
            {
                $service['instance'] = $this->app->make($service['class']);
                return TRUE;
            } else {
                return FALSE;
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['notifier'] = $this->app->share(function($app){
            $services = $this->initServices($app['config']->get('codengine/notifier::services'));
            return new NotificationService($services);
        });
    }

    public function provides()
    {
        return array('notifier');
    }

}