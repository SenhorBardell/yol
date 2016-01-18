<?php

use Faker\Factory as Faker;

class RolesTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		Role::truncate();

		Role::create(['name' => 'Admin']);
		Role::create(['name' => 'Moderator']);
		Role::create(['name' => 'User']);

		DB::table('role_user')->truncate();

//		$john = User::find(1);
//
//		$john->roles()->attach(1);
	}

}