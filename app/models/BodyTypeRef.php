<?php

/**
 * BodyTypeRef
 *
 */
class BodyTypeRef extends \Eloquent {
	protected $fillable = ['ru', 'az'];
	protected $hidden = ['created_at', 'updated_at', 'ru', 'az', 'models', 'vehicle_type_id'];

	public $timestamps = false;

	public function models() {
		return $this->belongsToMany('ModelRef');
	}

	public function toArray($locale = 'ru') {
		return $this->id;
//		return [
//			'id' => $this->id,
//			'name' => $this->$locale,
//			'models' => $this->models->map(function($model) {
//				return $model->id;
//			})
//		];
	}
}