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
        array_walk($services, function(&$service){
            $service['class_instance'] = $this->app->make($service['class']);
            if(isset($service['model']) && method_exists($service['class_instance'], 'setModel'))
            {
                $service['model_instance'] = $this->app->make($service['model']);
                $service['class_instance']->setModel($service['model_instance']);
            }
        });
        return $services;
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