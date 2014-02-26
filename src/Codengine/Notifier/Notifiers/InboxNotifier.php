<?php namespace Codengine\Notifier\Notifiers;

use Codengine\Notifier\Models\InboxInterface;

class InboxNotifier extends Notifier {
    /** @var \Codengine\Notifier\Models\Inbox $inboxModel */
    protected $inboxModel;

    public function setModel(InboxInterface $inboxModel)
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
        $entry = $this->inboxModel->newInstance($message);
        $entry->user_id = $destination['user_id'];
        $entry->save();
    }
} 