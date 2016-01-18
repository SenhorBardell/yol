<?php

use Faker\Factory as Faker;

class CommentsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		Comment::truncate();

		$posts = Post::all();

		foreach(range(1, 50) as $index)
		{
			Comment::create([
				'user_id' => $faker->numberBetween($min = 1, $max = 10),
				'post_id' => rand(1, $posts->count()),
				'text' =>  $faker->paragraph($nbSentences = 3),
				'attachment_id' => 0
			]);
		}
	}
}