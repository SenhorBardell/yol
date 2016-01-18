<?php

/**
 * VehicleTypeRef
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\BodyTypeRef[] $bodyTypes
 */
class VehicleTypeRef extends \Eloquent {
	protected $fillable = ['az', 'ru'];

	public function bodyTypes() {
		return $this->hasMany('BodyTypeRef', 'vehicle_type_id');
	}

	public function marks() {
		return $this->hasMany('MarkRef', 'mark_id');
	}

	public function toArray() {
		return $this->id;
	}

}