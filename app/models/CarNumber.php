<?php

class CarNumber extends Car {
	protected $table = 'cars_history';

	public function images() {
		return $this->morphMany('Image', 'imageable');
	}
}