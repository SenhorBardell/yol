<?php

use Illuminate\Database\Eloquent\Collection;

/**
 * ModelRef
 * @property string $name
 * @property-read Collection|\MarkRef[] $marks
 * @property-read Collection|\BodyTypeRef[] $bodyTypes
 * @property-read Collection|\VehicleTypeRef[] $vehicleTypes
 */
class ModelRef extends \Eloquent {
	protected $fillable = ['name'];
    protected $hidden = ['created_at', 'updated_at'];

	public $timestamps = false;

	public function vehicleTypes() {
		return $this->belongsTo('VehicleTypeRef');
	}

	public function bodyTypes() {
		return $this->belongsToMany('BodyTypeRef');
	}

	public function marks() {
		return $this->belongsTo('MarkRef', 'mark_id');
	}

}