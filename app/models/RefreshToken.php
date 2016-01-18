<?php

/**
 * RefreshToken
 *
 */
class RefreshToken extends \Eloquent {
	protected $fillable = ['user_id', 'token'];
}