<?php namespace Helpers;

class carHelper {

	public static function fetchCar(\User $user, $id) {
		$car = $user->carsHistory()->find($id);

		if ($car) return $car;

		$car = $user->cars()->find($id);

		if (!$car) return null;

		return static::createCarHistory($user, $car);
	}

	public static function createCarHistory(\User $user, \Car $car) {
		return $user->carsHistory()->create([
			'mark' => $car->mark != 0 ? $car->mark : null,
			'model' => $car->model != 0 ? $car->model : null,
			'year' => $car->year != 0 ? $car->year : null,
			'color' => $car->color != 0 ? $car->color : null,
			'body_type' => $car->body_type != 0 ? $car->body_type : null,
			'number' => $car->number,
			'vehicle_type' => $car->vehicle_type
		]);
	}

}