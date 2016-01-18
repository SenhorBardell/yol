<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Car
 *
 */
class Car extends Eloquent  {

	use SoftDeletingTrait;

	protected $fillable = ['mark', 'model', 'year', 'color', 'body_type', 'vehicle_type', 'number'];

	public static function validate($input) {
		$rules = array(
//			'year' => 'numeric',
			'number' => 'unique:cars|alpha_num',
//			'mark' => 'alpha',
//			'model' => 'alpha_spaces',
//			'color' => 'alpha_spaces',
//			'transmission' => 'alpha'
		);

		return Validator::make($input, $rules);
	}

	public static function checkSeria($number, $series = ['AA', 'PA', 'PM', 'YP']) {
		// Check if not foreign number
		if (is_numeric($number[0])) {
			$seria = $number[2] . $number[3];
			return in_array($seria, $series);
		}
		return false;
	}

	public $timestamps = false;

	public function getIdAttribute($value) {
		return (int)$value;
	}

	public function getYearAttribute($value) {
		return (int)$value;
	}

	public function getMarkAttribute($value) {
		return (int)$value;
	}

	public function getModelAttribute($value) {
		return (int)$value;
	}

	public function getColorAttribute($value) {
		return (int)$value;
	}

	public function getBodyTypeAttribute($value) {
		return (int)$value;
	}

	public function getVehicleTypeAttribute($value) {
		return (int)$value;
	}

	public function images() {
		return $this->morphMany('Image', 'imageable', 'imageable_type', 'imageable_id', 'id');
	}

	public function attachable() {
		return $this->morphTo('Attachment', 'attachable');
	}

	public function markRef() {
		return $this->belongsTo('MarkRef', 'mark');
	}

	public function modelRef() {
		return $this->belongsTo('ModelRef', 'model');
	}

	public function vehicleTypeRef() {
		return $this->belongsTo('VehicleTypeRef', 'vehicle_type');
	}

	public function bodyTypeRef() {
		return $this->belongsTo('BodyTypeRef', 'body_type');
	}

	public function user() {
		return $this->belongsTo('User');
	}
}