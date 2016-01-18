<?php

class MessageRemoved extends \Eloquent {
    protected $table = 'messages_removed';
    protected $fillable = ['message_id', 'user_id', 'timestamp'];
    public $timestamps = false;
    protected $primaryKey = 'message_id';
}