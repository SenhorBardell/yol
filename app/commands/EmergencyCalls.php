<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class EmergencyCalls extends Command {

	const CALLS = 90; // 2 emergency calls per day

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'calls:reset';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Resets the amount of available emergency calls in user';

	/**
	 * Create a new command instance.
	 *
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
		DB::statement('update users set urgent_calls = '. self::CALLS);
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
