<?php

class Geo extends Eloquent {
	protected $fillable = ['long', 'lat', 'location'];

	public $timestamps = false;

	public function posts() {
		return $this->morphToMany('Post', 'attachable');
	}

	public function getLongAttribute($value) {
		return (double)$value;
	}

	public function getLatAttribute($value) {
		return (double)$value;
	}
}