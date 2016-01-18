<?php

class Favorite extends Eloquent {
	public $fillable = ['post_id', 'user_id', 'created_at'];

	public $timestamps = false;

	public $primaryKey = 'post_id';

	public function category() {
		return $this->belongsTo('Category');
	}

	public function getDates() {
		return ['created_at'];
	}
}