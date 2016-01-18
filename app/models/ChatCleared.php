<?php

class ChatCleared extends \Eloquent {
    protected $table = 'chats_cleared';
    protected $fillable = ['message_id', 'user_id', 'timestamp'];
    public $timestamps = false;
    protected $primaryKey = 'message_id';
}