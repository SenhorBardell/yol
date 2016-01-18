<?php

class Notification extends \Eloquent {
    protected $table = 'notifications';
    protected $fillable = ['id', 'object', 'object_id', 'owner_id', 'event', 'timestamp', 'is_removed'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getTimestamp() {
        return $this->timestamp ? strtotime($this->timestamp) : $this->timestamp;
    }

    public function notifications_users() {
        return $this->hasMany('NotificationUser', 'notification_id');
    }
}

