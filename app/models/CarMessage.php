<?php

class CarMessage extends Eloquent {

	protected $fillable = [
		'text', 'user_id', 'user_car_id'
	];

	public $timestamps = false;

	public function scopeByLastId($query, $id) {
		return $query->where('id', '<', $id);
	}

	public function scopeNewById($query, $id) {
		return $query->where('id', '>', $id);
	}

	public function getDates() {
		return ['created_at', 'viewed_at', 'delivered_at'];
	}

	public function getCreatedAtAttribute($value) {
		if ($value)
			return strtotime($value);
		return null;
	}

	public function getViewedAtAttribute($value) {
		if ($value)
			return strtotime($value);
		return null;
	}

	public function getDeliveredAtAttribute($value) {
		if ($value)
			return strtotime($value);
		return null;
	}

	public function isUnread() {
		return !$this->viewed_at;
	}

	public function attachmentCar() {
		return $this->belongsTo('Car', 'car_id', 'id')->withTrashed();
	}

	public function attachmentImage() {
		return $this->belongsTo('MessageAttachment', 'image_id');
	}

}