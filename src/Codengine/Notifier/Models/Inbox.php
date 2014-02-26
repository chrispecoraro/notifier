<?php namespace Codengine\Notifier\Models;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model {
    protected $table = 'inbox';

    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeStatus($query, $status = 'unread')
    {
        return $query->where('status', $status);
    }
} 