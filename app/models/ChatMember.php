<?php

class ChatMember extends \Eloquent {
    protected $table = 'chats_members';
    protected $fillable = ['chat_id', 'user_id'];
    public $timestamps = false;
    protected $primaryKey = 'chat_id';
}