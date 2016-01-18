<?php

/**
 * AccessToken
 *
 * @property-read \User $user
 */
class AccessToken extends \Eloquent {
	protected $fillable = ['user_id', 'token'];
	
	public function user() {
		return $this->belongsTo('User');
	}

}