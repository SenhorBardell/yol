<?php

use Carbon\Carbon;
use Helpers\GenerationUtils\Generator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CompileRefs extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'refs:compile';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Compile cars (models, marks, vehicle types body tipes) and cities references';

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
	public function fire() {
		$locales = ['ru', 'az'];

		if ($this->option('with_categories')) {
			$this->info("Compile categories is on");
			MarkRef::with('models')->get()->each(function ($mark) {
				$this->line("Looking for {$mark->name}");
				$category = Generator::findOrCreate($mark->name);
				if ($category) {
					Generator::findOrCreate("Все о {$mark->name}", '', $category->id);
					$mark->models->each(function ($model) use ($mark, $category) {
						$this->line("Looking on {$model->name}");
						Generator::findOrCreate($mark->name.' '.$model->name, 'Все что касается этой модели.', $category->id);
					});
				}
			});
		}

		$configDate = Carbon::now()->toDateTimeString();
		$oldConfigDate = Config::get('database.refs-version');

		foreach ($locales as $locale) {

			$this->info('Compiling models');
			$tempDate = Carbon::now();
			$chunk = 200;
			$this->line("Batch {$chunk}");
			ModelRef::with('bodyTypes')->chunk($chunk, function ($models) use ($oldConfigDate, $configDate, $locale){
				Cache::forget("models-{$oldConfigDate}-{$locale}-part{$models->last()->id}");
				Cache::forever("models-{$configDate}-{$locale}-part{$models->last()->id}", $models->toArray());
				$this->line("End of chunk {$models->last()->id}");
			});
			Cache::forget("models-{$oldConfigDate}-{$locale}");
			Cache::forever("models-{$configDate}-{$locale}", ModelRef::all());
			$this->info("Finished compiling models. {$tempDate->diffInSeconds(Carbon::now(), true)} seconds");

			$this->info('Compiling marks');
			$tempDate = Carbon::now();
			Cache::forget("marks-{$oldConfigDate}-{$locale}");
			Cache::forever("marks-{$configDate}-{$locale}", MarkRef::with('vehicleTypes')->get()->toArray());
			$this->info("Finished compiling marks.{$tempDate->diffInSeconds(Carbon::now(), true)} seconds");

			$this->info('Compiling vehicle types');
			$tempDate = Carbon::now();
			Cache::forget("vehicle-types-{$oldConfigDate}-{$locale}");
			Cache::forever("vehicle-types-{$configDate}-{$locale}",VehicleTypeRef::all()->transform(function ($type) {
				return [
					'id' => $type->id,
					'name' => $type->ru
				];
			})->toArray());
			$this->info("Finished compiling vehicle types. {$tempDate->diffInSeconds(Carbon::now(), true)} seconds");

			$this->line('Compiling cities');
			$tempDate = Carbon::now();
			Cache::forget("cities-{$oldConfigDate}-{$locale}");
			Cache::forever("cities-{$configDate}-{$locale}", CityRef::all()->transform(function ($city) use($locale){
				return ['name' => $city->$locale, 'id' => $city->id];
			})->toArray());
			$this->info("Finished compiling cities. {$tempDate->diffInSeconds(Carbon::now(), true)} seconds");

			$this->info('Compiling body types');
			$tempDate = Carbon::now();
			Cache::forget("body-types-{$oldConfigDate}-{$locale}");
			Cache::forever("body-types-{$configDate}-{$locale}", BodyTypeRef::all()->transform(function($type) use($locale){
				return [
					'id' => $type->id,
					'name' => $type->$locale
				];
			})->toArray());
			$this->info("Finishing compiling body types. {$tempDate->diffInSeconds(Carbon::now(), true)} seconds");

		}

		Cache::forever('database.refs-version', $configDate);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('-with_categories', InputArgument::OPTIONAL, 'Look into every mark and model and create categories accordingly', false),
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
			array('with_categories', 'c', InputOption::VALUE_NONE, 'Look into every mark and model and create categories accordingly'),
		);
	}

}
