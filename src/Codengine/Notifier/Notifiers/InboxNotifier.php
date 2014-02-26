<?php namespace Codengine\Notifier\Notifiers;

class InboxNotifier extends Notifier {
    protected $inboxModel;

    public function __construct(\Codengine\Notifier\Models\Inbox $inboxModel)
    {
        $this->inboxModel = $inboxModel;
    }

    public function getNotifierKey()
    {
        return 'inbox';
    }

    public function sendNotification($destination, $view, $data)
    {

    }

    public function prepareDestination($destination)
    {
        $destination['user_id'] = $this->obtainUserInfo('id');

        return $destination;
    }
} 