<?php namespace Helpers\GenerationUtils;

use \Category;

class Generator {

	/**
	 * Finds a category by name and if not found create one
	 *
	 * @param string $title
	 * @param string $description
	 * @return \Category
	 */
	public static function findOrCreate($title, $description = null, $parent_id = null) {
		if (!Category::where('title', $title)->first()) {
			return Category::create(['title' => $title, 'description' => $description, 'parent_id' => $parent_id]);
		}
		return null;
	}

}