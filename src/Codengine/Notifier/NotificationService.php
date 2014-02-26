<?php namespace Codengine\Notifier;

use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    protected $notifiers = array();

    public function __construct(array $notifiers = array())
    {
        $this->notifiers = $notifiers;
    }

    public function sendNotification(Notification $notification, array $notifiers = array())
    {
        if (!empty($notifiers))
        {
            $notifiers = array_filter($this->notifiers, function($notifier) use ($notifiers) {
                return in_array($notifier['instance']->getNotifierKey(), $notifiers);
            });
        }
        else
        {
            $notifiers = $this->notifiers;
        }

        foreach ($notifiers as $notifier) {
            $notifier['instance']->notify($notification);
        }
    }

    public function createNotification(Model $user, $view = null)
    {
        return new Notification($user, $view);
    }

    public function __call($method, $parameters)
    {
        $matches = array();
        if (preg_match('/send(.+)Notification/', $method, $matches) === 1)
        {
            $notificationType = strtolower($matches[1]);
            array_push($parameters, array($notificationType));
            return call_user_func_array(array($this, 'sendNotification'), $parameters);
        }

        return call_user_func_array(array($this, $method), $parameters);
    }
}