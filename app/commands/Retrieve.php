<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Retrieve extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'retrieve:images';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		$this->deleteFiles(['thumbnail', 'gallery', 'origin', 'avatar_origin', 'avatar_large', 'avatar_middle', 'avatar_small']);

		$s3 = AWS::get('s3');
		$prefix = 'https://yolanothertest.s3-us-west-2.amazonaws.com/';

		$objects = $s3->getIterator('listObjects', ['Bucket' => 'yolanothertest']);

		foreach($objects as $object) {

			$imageUrl = $prefix.$object['Key'];

			if ($thumbnail = Image::where('thumbnail', $prefix.$object['Key'])->whereNotNull('imageable_id')->first()) {

				if ($thumbnail) {
					File::append(storage_path('thumbnail.csv'), "{$thumbnail->id}, {$object['Key']}, {$thumbnail->imageable_type}, {$thumbnail->imageable_id}, {$object['Size']}, " .$this->getImageSize($imageUrl). PHP_EOL);

					$this->line("{$thumbnail->id} thumbnail {$object['Key']} {$thumbnail->imageable_type} {$thumbnail->imageable_id} {$object['Size']}");
				}
			} elseif ($large = Image::where('regular', $prefix . $object['Key'])->whereNotNull('imageable_id')->first()) {

				if ($large) {
					File::append(storage_path('gallery.csv'), "{$large->id},  {$object['Key']}, {$large->imageable_type}, {$large->imageable_id}, {$object['Size']}, " .$this->getImageSize($imageUrl). PHP_EOL);
					$this->line("{$large->id} gallery {$object['Key']} {$large->imageable_type} {$large->imageable_id} {$object['Size']}");
				}
			} elseif ($origin = Image::where('origin', $prefix . $object['Key'])->whereNotNull('imageable_id')->first()) {

				if ($origin) {
					File::append(storage_path('origin.csv'), "{$origin->id}, {$object['Key']}, {$origin->imageable_type}, {$origin->imageable_id}, {$object['Size']}, ".$this->getImageSize($imageUrl).PHP_EOL);
				}
			} elseif ($avatarOrigin = User::where('img_origin', $prefix.$object['Key'])->first()) {
				if ($avatarOrigin) {
					File::append(storage_path('avatar_origin.csv'), "{$avatarOrigin->id}, {$object['Key']}, {$object['Size']}, ".$this->getImageSize($imageUrl).PHP_EOL);
				}

			} elseif ($avatarLarge = User::where('img_large', $prefix.$object['Key'])->first()) {

				if ($avatarLarge) {
					File::append(storage_path('avatar_large.csv'), "{$avatarLarge->id}, {$object['Key']}, {$object['Size']}, ".$this->getImageSize($imageUrl).PHP_EOL);
				}

			} elseif ($avatarMiddle = User::where('img_middle', $prefix.$object['Key'])->first()) {

				if ($avatarMiddle) {
					File::append(storage_path('avatar_middle.csv'), "{$avatarMiddle->id}, {$object['Key']}, {$object['Size']}, ".$this->getImageSize($imageUrl).PHP_EOL);
				}

			} elseif ($avatarSmall = User::where('img_small', $prefix.$object['Key'])->first()) {

				if ($avatarSmall) {
					File::append(storage_path('avatar_small.csv'), "{$avatarSmall->id}, {$object['Key']}, {$object['Size']}, ".$this->getImageSize($imageUrl).PHP_EOL);
				}

			}
		}
	}

	private function deleteFiles(array $files) {
		foreach ($files as $file) {
			File::delete(storage_path($file.'csv'));
		}
	}

	private function getImageSize($url) {
		$object = getimagesize($url);
		return "$object[0], $object[1]";
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
