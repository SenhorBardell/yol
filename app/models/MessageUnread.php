<?php

class MessageUnread extends \Eloquent {
    protected $table = 'messages_unread';
    protected $fillable = ['message_id', 'user_id'];
    public $timestamps = false;
    protected $primaryKey = 'message_id';
}