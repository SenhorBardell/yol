<?php

use Illuminate\Database\Eloquent\Collection;

class Emergency extends \Eloquent {
	protected $fillable = ['number', 'created_at', 'receiver', 'status'];
	public $timestamps = false;

	public function getDates() {
		return ['created_at', 'delivered_at'];
	}

	public function getCreatedAtAttribute($value) {
		return strtotime($value);
	}

	public function getDeliveredAtAttribute($value) {
		if ($value)
			return strtotime($value);
		else return $value;
	}

	public function getComplainedAtAttribute($value) {
		if ($value)
			return strtotime($value);
		else return $value;
	}

	public function senderU() {
		return $this->belongsTo('User', 'sender');
	}

	public function receiverU() {
		return $this->belongsTo('User', 'receiver');
	}

	public function getMembersTokens() {
		$devices = new Collection();
		$devices = $devices->merge($this->senderU->devices);
		$devices = $devices->merge($this->receiverU->devices);
		return $devices;
	}
}