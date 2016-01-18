<?php

class Subscription extends Eloquent {
	protected $table = 'subscriptions';

	public $timestamps = false;

	public function posts() {
		return $this->hasMany('Post');
	}
}