<?php

use Faker\Factory as Faker;

class CarsTableSeeder extends Seeder {

	public function run() {
		$faker = Faker::create();

		Car::truncate();

		foreach(range(1, 11) as $index) {
			Car::create([
				'user_id' => $index,
				'mark' => $faker->word,
				'model' => $faker->word,
				'year' => $faker->hexcolor,
				'color' => $faker->colorName,
				'transmission' => $faker->word,
				'body' => $faker->word,
				'number' => $faker->word
			]);
		}
	}

}