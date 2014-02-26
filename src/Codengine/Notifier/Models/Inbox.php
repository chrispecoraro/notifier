<?php namespace Codengine\Notifier\Models;

use Illuminate\Database\Eloquent\Model;

class Inbox extends Model implements InboxInterface {
    protected $table = 'inbox';
    protected $fillable = array('user_id', 'type', 'subject', 'body');
    protected $guarded = array('status', 'created_at', 'updated_at');

    public function scopeUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeStatus($query, $status = 'unread')
    {
        return $query->where('status', $status);
    }
}