<?php

class CarVehicleTypeRelations extends Seeder {
	public function run() {
		$bodyTypes = [21, 22, 23, 24, 25, 26, 27, 28,
			29, 30, 31, 32, 33, 34, 35, 36
		];
		ModelRef::where('vehicle_type_id', 2)->chunk(100, function ($models) use($bodyTypes) {
			$models->each(function ($model) use ($bodyTypes) {
				$model->bodyTypes()->attach($bodyTypes);
			});
		});
	}
}