<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class linkVTypes extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'link-vtypes';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create empty marks and models and link them to vehicle types';

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
	public function fire()
	{
		//Buses

		$this->doMagic(4, [44, 45, 46, 47, 48, 49]);

		//Trucks

		$this->doMagic(1, [
			1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17,
			18, 19, 20
		]);

		//Moto

		$this->doMagic(3, [38, 39, 40, 41, 42, 43]);
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

	private function doMagic($id, $bodyTypes) {
		MarkRef::whereHas('vehicleTypes', function ($q) use($id) {
			return $q->where('vehicle_type_ref_id', $id);
		})->get()->each(function ($mark)  use($id, $bodyTypes) {
			$this->line($mark->name);
			$model = new ModelRef(['name' => '']);
			$model->vehicle_type_id = $id;
			$mark->models()->save($model);

			$model->bodyTypes()->attach($bodyTypes);
		});
	}
}
