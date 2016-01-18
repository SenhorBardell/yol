<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Device extends Eloquent {

//	use SoftDeletingTrait;

	protected $fillable = ['udid', 'auth_token', 'phone', 'user_id'];

	protected $hidden = ['udid', 'updated_at', 'created_at', 'user_id', 'auth_token'];

	public $timestamps = false;

	public function user() {
		return $this->belongsTo('User');
	}

	public function getIdAttribute($value) {
		return (int)$value;
	}

	public function getPhoneAttribute($value) {
		return (int)$value;
	}

	public function pushTokens() {
		return $this->hasMany('PushToken');
	}
}