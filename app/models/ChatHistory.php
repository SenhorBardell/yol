<?php

class ChatHistory extends \Eloquent {
    protected $table = 'chats_history';
    protected $fillable = ['id', 'chat_id', 'event', 'timestamp'];
    public $timestamps = false;
    protected $primaryKey = 'id';
}