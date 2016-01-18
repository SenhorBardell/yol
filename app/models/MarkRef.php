<?php

/**
 * MarkRef
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\ModelRef[] $models
 * @property string $name
 * @property integer $id
 */
class MarkRef extends \Eloquent {
	protected $fillable = ['name'];
	protected $hidden = ['created_at', 'updated_at', 'vehicle_type_id'];

	public $timestamps = false;

    public function vehicleTypes() {
        return $this->belongsToMany('VehicleTypeRef');
    }

    public function models() {
        return $this->hasMany('ModelRef', 'mark_id');
    }

}