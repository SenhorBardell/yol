<?php

use Faker\Factory as Faker;

class PostsTableSeeder extends Seeder {

	private $categoriesCount;

	public function run()
	{
		$faker = Faker::create();

		$categories = Category::where('parent_id', 0)->get();
		$this->categoriesCount = $categories->count();

		Post::truncate();

		foreach(range(1, 20) as $index)
		{
			Post::create([
				'text' =>  $faker->paragraph($nbSentences = 3),
				'user_id' => $faker->numberBetween($min = 1, $max = 10),
				'category_id' => $this->randCat()
			]);
		}
	}

	private function randCat() {
		return rand(1, $this->categoriesCount);
	}

}