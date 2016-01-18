<?php

class SMS extends Eloquent {
	protected $table = 'temp_auth';

	protected $fillable = ['phone', 'code', 'token', 'device', 'sent_at'];

	public $timestamps = false;
}