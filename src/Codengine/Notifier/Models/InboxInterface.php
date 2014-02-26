<?php namespace Codengine\Notifier\Models;

interface InboxInterface {
    public function scopeUser($query, $userId);
    public function scopeStatus($query, $status = 'unread');
} 