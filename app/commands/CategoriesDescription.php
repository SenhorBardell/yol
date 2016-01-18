<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CategoriesDescription extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'categories:update_description';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		dd('test');
		$vehicleTypes = VehicleTypeRef::all();
		Category::where('id', '>', 58)->whereNull('parent_id')->get()->each(function ($category) use ($vehicleTypes) {
			$category->categories->each(function ($subCategory) use ($category, $vehicleTypes) {
//				$vehicleTypes->each(function ($vehicleType) use($category, $subCategory) {
//					if ($subCategory->title != "{$vehicleType->ru} {$category->name}") {
						$subCategory->description = "Все об {$subCategory->title}";
//					} else {
//						 different description
//					}
//				});
				if ($subCategory->save()) {
					$this->line($subCategory->title);
					$this->line($subCategory->description.PHP_EOL);
				}
			});
		});
		$marks = MarkRef::all();
		Category::where('id', '>', 58)->whereNotNull('parent_id')->get()->each(function ($category) use ($vehicleTypes, $marks) {
			$marks->each(function ($mark) use ($category, $vehicleTypes) {
				$vehicleTypes->each(function ($vehicleType) use ($category, $mark) {
					if ($category->title == "{$vehicleType->ru} {$mark->name}") {
						$newTitle = $this->morph($vehicleType) . $mark->name;
						$this->line("{$category->title} to {$newTitle}");
						// $category->title = $newTitle;
						// $category->save();
					}
				});
			});
		});
	}

	private function morph($vehicleType) {

	}

}
