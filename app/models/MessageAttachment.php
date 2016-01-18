<?php

class MessageAttachment extends \Eloquent {
    protected $table = 'messages_attachments';
    protected $fillable = ['id', 'chat_id', 'origin', 'thumb', 'width', 'height'];
    public $timestamps = false;
    protected $primaryKey = 'id';
}