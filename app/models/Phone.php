<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Phone extends Eloquent {

	use SoftDeletingTrait;

	public $timestamps = false;

	protected $fillable = ['number'];
	protected $hidden = ['user_id'];

	public function user() {
		return $this->belongsTo('User');
	}

	//FIXME make sure than phone returning as integer
}