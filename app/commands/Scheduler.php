<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Scheduler extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'scheduler:start';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run schedule manager. (Preferably on clock)';

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
//		$this->call('queue:work', ['--daemon']);
		while (true) {
			$this->call($this->argument('execFunc'));
			// A little workaround about heroku's one process nodes
			// cant run anymore, running directly in manager
//			foreach (range(0, 5) as $counter) {
//				$this->line('Queue called');
//				$this->call('queue:work');
//			}
			sleep($this->option('seconds'));
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['execFunc', InputArgument::REQUIRED, 'Command to be executed'] //cant use command, already exists
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['seconds', 's', InputArgument::OPTIONAL, 'Time period (in seconds)', 10]
		];
	}

}
