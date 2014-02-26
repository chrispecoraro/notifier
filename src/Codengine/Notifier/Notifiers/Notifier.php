<?php namespace Codengine\Notifier\Notifiers;

use Illuminate\Support\Facades\Config;
use Codengine\Notifier\Notification;

abstract class Notifier
{
    /** @var Notification $notification */
    protected $notification;

    abstract public function getNotifierKey();
    abstract public function prepareDestination();
    abstract public function sendNotification($destination, $view = null);

    public function notify(Notification $notification)
    {
        $this->setNotification($notification);

        if ($this->notificationsEnabled())
        {
            $view = Config::get('codengine/notifier::views_folder') . '.' . $this->getNotifierKey() . '.' . $notification->getView();
            $destination = $this->prepareDestination();
            $this->sendNotification($destination, $view);
        }
    }

    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function notificationsEnabled()
    {
        return $this->getOption('enabled');
    }

    protected function getOption($key)
    {
        $key = $this->getKeyPrefix() . $key;
        $value = Config::get($key);
        return $value;
    }

    protected function getUserInfo($info)
    {
        $callback = $this->getOption('getter_' . $info);
        return $callback->__invoke($this->notification->getUser());
    }

    private function getKeyPrefix()
    {
        return 'codengine/notifier::services.' . $this->getNotifierKey() . '.';
    }
}