<?php

use Illuminate\Support\Facades\Config;
use Codengine\Notifier\NotificationService;

class NotificationServiceTest extends TestCase 
{
    protected $sms;
    protected $email;

    protected function createService()
    {
        $notifierSms = Mockery::mock('Codengine\Notifier\Notifiers\SMSNotifier');
        $this->sms = $notifierSms;
        $notifierEmail = Mockery::mock('Codengine\Notifier\Notifiers\EmailNotifier');
        $this->email = $notifierEmail;
        $notifiers = array(
            'email' => array(
                'enabled' => true,
                'instance' => $notifierEmail
            ),
            'sms' => array(
                'enabled' => true,
                'instance' => $notifierSms
            )
        );

        $service = new NotificationService($notifiers);

        return $service;
    }

    public function testCallsAllNotifiersByDefault()    
    {
        $service = $this->createService();
        $user = 'foo';
        $view = 'bar';
        $viewData = 'fooData';
        $subject = 'foobar';

        $notification = Mockery::mock('Codengine\Notifier\Notification');
        $notification->shouldReceive('getUser')->andReturn($user);
        $notification->shouldReceive('getView')->andReturn($view);
        $notification->shouldReceive('getViewData')->andReturn($viewData);
        $notification->shouldReceive('getSubject')->andReturn($subject);

        $this->sms->shouldReceive('notify')->once()->with($user, $view, $viewData, $subject);
        $this->email->shouldReceive('notify')->once()->with($user, $view, $viewData, $subject);

        $service->sendNotification($notification);
    }

    public function testCallsSelectedNotifiersOnly()
    {
        $service = $this->createService();

        $this->sms->shouldReceive('getNotifierKey')->once()->andReturn('sms');
        $this->email->shouldReceive('getNotifierKey')->once()->andReturn('email');

        $notification = Mockery::mock('Codengine\Notifier\Notification');
        $notification->shouldReceive('getUser');
        $notification->shouldReceive('getView');
        $notification->shouldReceive('getViewData');
        $notification->shouldReceive('getSubject');

        $this->sms->shouldReceive('notify')->once();

        $service->sendNotification($notification, array('sms'));
    }

    public function testCreateNotification()
    {
        $service = $this->createService();
        $user = 'foo';
        $view = 'bar';

        $notification = $service->createNotification($user, $view);

        $this->assertEquals($notification->getUser(), $user);
        $this->assertEquals($notification->getView(), $view);

        $this->assertEquals($notification->getViewData(), array());
        $this->assertNull($notification->getSubject());
    }

    public function testDynamicMethodCallsSpecificNotifier()
    {
        $service = $this->createService();

        $this->sms->shouldReceive('getNotifierKey')->once()->andReturn('sms');
        $this->email->shouldReceive('getNotifierKey')->once()->andReturn('email');

        $notification = Mockery::mock('Codengine\Notifier\Notification');
        $notification->shouldReceive('getUser');
        $notification->shouldReceive('getView');
        $notification->shouldReceive('getViewData');
        $notification->shouldReceive('getSubject');

        $this->sms->shouldReceive('notify')->once();

        $service->sendSMSNotification($notification);
    }

}