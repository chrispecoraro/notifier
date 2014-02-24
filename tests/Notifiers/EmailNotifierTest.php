<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class EmailNotifierTest extends TestCase 
{
    public function testSendEmailNotification()
    {
        Mail::shouldReceive('queue')->once();

        $email = new \Codengine\Notifier\Notifiers\EmailNotifier();
        
        Config::shouldReceive('get')->once()->with('notifier::email.enabled')->andReturn(true);
        Config::shouldReceive('get')->once()->with('notifier::views_folder')->andReturn('viewsFolder');
        Config::shouldReceive('get')->once()->with('notifier::email.from_email')->andReturn('fromEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.getter_email')->andReturn(function($user){
            return 'getterEmail';
        });
        Config::shouldReceive('get')->once()->with('notifier::email.getter_name')->andReturn(function($user){
            return 'userName';
        });

        $email->notify('fooUser', 'fooView', array('foo' => 'bar'));
    }

    public function testPassesCorrectEmailParameters()
    {
        $email = Mockery::mock('Codengine\Notifier\Notifiers\EmailNotifier')->makePartial();
        
        Config::shouldReceive('get')->once()->with('notifier::email.enabled')->andReturn(true);
        Config::shouldReceive('get')->once()->with('notifier::views_folder')->andReturn('viewsFolder');
        Config::shouldReceive('get')->once()->with('notifier::email.from_email')->andReturn('fromEmail');
        Config::shouldReceive('get')->once()->with('notifier::email.getter_email')->andReturn(function($user){
            return 'getterEmail';
        });
        Config::shouldReceive('get')->once()->with('notifier::email.getter_name')->andReturn(function($user){
            return 'userName';
        });

        $destination = array(
            'email' => 'getterEmail',
            'name' => 'userName',
            'subject' => null,
            'from_email' => 'fromEmail'
        );

        $email->shouldReceive('sendNotification')->once()->with($destination, 'viewsFolder.email.fooView', array('foo' => 'bar', 'user' => 'fooUser'));

        $email->notify('fooUser', 'fooView', array('foo' => 'bar'));
    }

}