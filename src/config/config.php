<?php

return array(
    'views_folder' => 'notifications',
    'services' => array(
        'email' => array(
            'class' => 'Codengine\Notifier\Notifiers\EmailNotifier',
            'enabled' => true,
            'from_email' => 'notifications@email.com',
            'from_name' => 'Notifications',
            'getter_email' => function($user) {
                return $user->email;
            },
            'getter_name' => function($user) {
                return $user->first_name . ' ' . $user->last_name;
            },
        ),
        'inbox' => array(
            'class' => 'Codengine\Notifier\Notifiers\InboxNotifier',
            'model' => 'Codengine\Notifier\Models\Inbox',
            'enabled' => true,
            'per_page' => 10,
            'getter_id' => function($user) {
                return $user->id;
            },
        )
    )
);