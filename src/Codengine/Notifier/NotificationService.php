<?php namespace Codengine\Notifier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

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
                return ($notifier['enabled'] && in_array($notifier['class_instance']->getNotifierKey(), $notifiers) ? TRUE : FALSE);
            });
        }
        else
        {
            $notifiers = array_filter($this->notifiers, function($notifier) {
                return ($notifier['enabled'] ? TRUE : FALSE);
            });
        }

        array_walk($notifiers, function($notifier) use ($notification){
            $notifier['class_instance']->notify($notification);
        });
    }

    public function inboxGetUnreadCount($user_id)
    {
        return $this->notifiers['inbox']['model_instance']->user($user_id)
            ->status('unread')
            ->count();
    }

    public function inboxGetPaginated($user_id)
    {
        return $this->notifiers['inbox']['model_instance']->user($user_id)
            ->orderBy('created_at', 'desc')
            ->paginate($this->notifiers['inbox']['per_page']);
    }

    public function inboxGetMessage($user_id, $message_id)
    {
        return $this->notifiers['inbox']['model_instance']->user($user_id)
            ->where('id', $message_id)
            ->first();
    }

    public function inboxMarkRead($user_id, $message_id)
    {
        $this->notifiers['inbox']['model_instance']->where('user_id', $user_id)
            ->where('id', $message_id)
            ->update(array('status' => 'read'));
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