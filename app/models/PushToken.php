<?php

class PushToken extends \Eloquent {
    protected $table = 'push_tokens';
    protected $fillable = ['device_id', 'token', 'platform'];
    public $timestamps = false;
    protected $primaryKey = 'device_id';
}