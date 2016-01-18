<?php

class NotificationUser extends \Eloquent {
    protected $table = 'notifications_users';
    protected $fillable = ['id', 'notification_id', 'subject', 'subject_id', 'user_id', 'timestamp', 'is_removed'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getTimestamp() {
        return $this->timestamp ? strtotime($this->timestamp) : $this->timestamp;
    }
}

