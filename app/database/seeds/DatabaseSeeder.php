<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

//		$this->call('UsersTableSeeder');
//
//		$this->call('CarsTableSeeder');

//		$this->call('CategoriesTableSeeder');

//		$this->call('PostsTableSeeder');

//		$this->call('CommentsTableSeeder');

//		$this->call('RolesTableSeeder');

//		$this->call('PermissionsTableSeeder');

//        $this->call('CityRefsTableSeeder');
//		$this->call('VehicleTypeRefsTableSeeder');
//		$this->call('MarkRefsTableSeeder');

//		$this->call('CarVehicleTypeRelations');

		$this->call('CategoriesCarsTableSeeder');
	}

}
