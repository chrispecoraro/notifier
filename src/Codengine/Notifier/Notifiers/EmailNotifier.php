<?php namespace Codengine\Notifier\Notifiers;

use Illuminate\Support\Facades\Mail;

class EmailNotifier extends Notifier
{
    public function getNotifierKey()
    {
        return 'email';
    }

    public function prepareDestination()
    {
        $destination = [
            'from_name' => $this->getOption('from_name'),
            'from_email' => $this->getOption('from_email'),
            'to_email' => $this->getUserInfo('email'),
            'to_name' => $this->getUserInfo('name'),
            'subject' => $this->notification->getSubject()
        ];

        return $destination;
    }

    public function sendNotification($destination, $view = null)
    {
        Mail::queue($view, $this->notification->getViewData(), function($message) use ($destination)
        {
            $message->to($destination['to_email'], $destination['to_name'])
                ->subject($destination['subject']);

            if (!is_null($destination['from_email']))
            {
                $message->from($destination['from_email'], (!is_null($destination['from_name']) ? $destination['from_name'] : null));
            }
        });
    }
}