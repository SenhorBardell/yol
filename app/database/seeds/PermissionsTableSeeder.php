<?php

use Faker\Factory as Faker;

class PermissionsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		Permission::truncate();
		DB::table('permission_role')->truncate();

		Permission::create([
			'action' => 'User.create',
		]);

		Permission::create([
			'action' => 'User.view',
		]);

		Permission::create([
			'action' => 'User.update',
		]);

		Permission::create([
			'action' => 'User.delete',
		]);

		Permission::create([
			'action' => 'Category.create',
		]);

		Permission::create([
			'action' => 'Category.view',
		]);

		Permission::create([
			'action' => 'Category.update',
		]);

		Permission::create([
			'action' => 'Category.delete',
		]);

		Permission::create([
			'action' => 'Post.create',
		]);

		Permission::create([
			'action' => 'Post.view',
		]);

		Permission::create([
			'action' => 'Post.update',
		]);

		Permission::create([
			'action' => 'Post.delete',
		]);

		Permission::create([
			'action' => 'Comment.create',
		]);

		Permission::create([
			'action' => 'Comment.view',
		]);

		Permission::create([
			'action' => 'Comment.update',
		]);

		Permission::create([
			'action' => 'Comment.delete',
		]);

		Permission::create([
			'action' => 'Role.create',
		]);

		Permission::create([
			'action' => 'Role.view',
		]);

		Permission::create([
			'action' => 'Role.update',
		]);

		Permission::create([
			'action' => 'Role.delete',
		]);

		Permission::create([
			'action' => 'Permission.create',
		]);

		Permission::create([
			'action' => 'Permission.view',
		]);

		Permission::create([
			'action' => 'Permission.update',
		]);

		Permission::create([
			'action' => 'Permission.delete',
		]);

		$admin = Role::find(1);
		$admin->permissions()->sync(range(1, 24));		

	}

}