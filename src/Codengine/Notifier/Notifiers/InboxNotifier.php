<?php namespace Codengine\Notifier\Notifiers;

use Codengine\Notifier\Models\Inbox;

class InboxNotifier extends Notifier {
    protected $inboxModel;

    public function __construct(Inbox $inboxModel)
    {
        $this->inboxModel = $inboxModel;
    }

    public function getNotifierKey()
    {
        return 'inbox';
    }

    public function prepareDestination()
    {
        $destination = array(
            'user_id' => $this->getUserInfo('id')
        );
        return $destination;
    }

    private function prepareMessage()
    {
        $message = array(
            'type' => ($this->notification->getType() ?: 'default'),
            'subject' => $this->notification->getSubject(),
            'body' => $this->notification->getBody(),
            'action' => $this->notification->getAction()
        );
        return $message;
    }

    public function sendNotification($destination, $view = null)
    {
        $message = $this->prepareMessage();
        $entry = new $this->inboxModel($message);
        $entry->user_id = $destination['user_id'];
        $entry->save();
    }
} 