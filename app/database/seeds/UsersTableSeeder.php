<?php

use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		User::truncate();
		Phone::truncate();
		SMS::truncate();

		User::create([
			'name' => 'John',
			'email' => 'senhorbardell@gmail.com',
			'password' => 'test',
			'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
			'sex' => true,
			'city' => $faker->numberBetween(0, 3),
//			'completed' => 2
		]);
		foreach(range(1, 10) as $index)
		{
			User::create([
				'name' => $faker->firstName,
				'email' => $faker->email,
				'birthday' => $faker->date($format = 'Y-m-d', $max = 'now'),
				'city' => 2,
				'sex' => $faker->boolean(),
				'about' => $faker->paragraph($nbSentences = 3),
				'password' => 'test',
//				'completed' => 2
			]);
		}

	}

}