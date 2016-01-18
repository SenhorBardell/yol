<?php

use Carbon\Carbon;
use Predis\Collection\Iterator;

class ReferencesController extends ApiController {

	public $avaibleLocales = ['ru', 'az'];
	public $locale;

	public function redirect() {
		$locale = in_array(Request::header('Locale'), $this->avaibleLocales) ? Request::header('Locale') : $this->avaibleLocales[0];
		$date = Input::get('date');
		if ($date <= Config::get('database.refs-version')) {
			$guzzle = new GuzzleHttp\Client();
			$response = $guzzle->get('https://dl.dropboxusercontent.com/u/7734467/refs-ru.json');
			$resp_arr = $response->json();
//			array_merge($resp_arr, ['date' => Carbon::now()->toDateTimeString()]);
			$resp_arr['date'] = Carbon::now()->toDateTimeString();
			return $this->respond($resp_arr);
//			return Redirect::to('https://yolanothertest.s3-us-west-2.amazonaws.com/refs-' . $locale . '.json');
		}

		return $this->respond(['date' => Carbon::now()->toDateTimeString()]);
	}

	public function all2() {
		$this->locale = in_array(Request::header('Locale'), $this->avaibleLocales) ? Request::header('Locale') : $this->avaibleLocales[0];
		$date = Input::get('date');
		$configDate = Cache::get('database.refs-version');

		if ($date <= $configDate) {

			$redis = Redis::connection();

			$conn = parse_url(getenv('REDISCLOUD_URL'));
			if(isset($conn['pass'])) {
				$redis->auth($conn['pass']);
			}

			$models = [];
			foreach (new Iterator\Keyspace($redis, "laravel:models-{$configDate}-{$this->locale}-part*") as $key) {
				$models = array_merge($models, Cache::get(preg_replace('/laravel\:/','', $key)));
			}

			$response = [
				'models' => $models,
				'marks' => Cache::get("marks-{$configDate}-{$this->locale}"),
				'vehicle-types' => Cache::get("vehicle-types-{$configDate}-{$this->locale}"),
				'cities' => Cache::get("cities-{$configDate}-{$this->locale}"),
				'body-types' => Cache::get("body-types-{$configDate}-{$this->locale}"),
				'date' => Carbon::now()->toDateTimeString()
			];

//			$response['models'] = Cache::remember("models-{$configDate}-{$this->locale}", $minutes, function() {
//				return ModelRef::with('bodyTypes')->get();
//			});
//
//			$response['marks'] = Cache::remember("marks-{$configDate}-{$this->locale}", $minutes, function() {
//				return MarkRef::with('vehicleTypes')->get();
//			});
//
//			$response['vehicle-types'] = Cache::remember("vehicle-types-{$configDate}-{$this->locale}", $minutes, function() {
//				return VehicleTypeRef::all()->transform(function ($type) {
//					return [
//						'id' => $type->id,
//						'name' => $type->ru
//					];
//				});
//			});

//			$response['cities'] = Cache::remember("cities-{$configDate}-{$this->locale}", $minutes, function() {
//				return $this->transformCities(CityRef::all());
//			});

//			$response['body-types'] = Cache::remember("body-types-{$configDate}-{$this->locale}", $minutes, function() {
//				return BodyTypeRef::all()->transform(function($type) {
//					$locale = $this->locale;
//					return [
//						'id' => $type->id,
//						'name' => $type->$locale
//					];
//				});
//			});

//			$response['date'] = Carbon::now()->toDateTimeString();
		} else {
			$response['date'] = Carbon::now()->toDateTimeString();
		}

		return $this->respond($response);
	}

	public function cities() {
		$this->locale = in_array(Request::header('Locale'), $this->avaibleLocales) ? Request::header('Locale') : $this->avaibleLocales[0];
		$configDate = Cache::get('database.refs-version');

		$redis = Redis::connection();

		$conn = parse_url(getenv('REDISCLOUD_URL'));
		if(isset($conn['pass'])) {
			$redis->auth($conn['pass']);
		}

		return $this->respond(Cache::get("cities-{$configDate}-{$this->locale}"));
	}

	public function all() {
		$this->locale = in_array(Request::header('Locale'), $this->avaibleLocales) ? Request::header('Locale') : $this->avaibleLocales[0];
		$date = Input::has('date') ? Input::get('date') : null;

		if (is_null($date)) {
			$cities = CityRef::all();
			$vehicles = VehicleTypeRef::all();
			$bodyTypes = BodyTypeRef::all();
			$marks = MarkRef::all();
			$models = ModelRef::all();

		} else {
			$cities = CityRef::where('updated_at', '>=', $date)->get();
			$vehicles = VehicleTypeRef::where('updated_at', '>=', $date)->get();
			$marks = MarkRef::where('updated_at', '>=', $date)->get();
			$bodyTypes = BodyTypeRef::where('updated_at', '>=', $date)->get();
			$models = ModelRef::where('updated_at', '>=', $date)->get();
		}

		$response['vehicle-types'] = $this->transformVehicles($vehicles);

		$response['body-types'] = $this->transformBodyTypes($bodyTypes);

		$response['cities'] = $this->transformCities($cities);

		$response['models'] = $models;

		$response['marks'] = $marks;

		$response['date'] = Carbon::now()->toDateTimeString();

		return $this->respond($response);
	}

	private function transformVehicles($vehicles) {
		if ($vehicles->isEmpty()) return [];
		$locale = $this->locale;
		foreach ($vehicles as $vehicle)
			$vehicleResult[] = [
				'name' => $vehicle->$locale,
				'id' => $vehicle->id,
			];

		return $vehicleResult;
	}

	private function transformBodyTypes($bodyTypes) {
		if ($bodyTypes->isEmpty()) return [];
		$locale = $this->locale;
		foreach($bodyTypes as $bodyType) {
			$bodyTypeResult[] = [
				'name' => $bodyType->$locale,
				'id' => $bodyType->id,
				'vehicle_type_id' => $bodyType->vehicle_type_id
			];
		}

		return $bodyTypeResult;
	}

	private function transformCities($cities) {
		if ($cities->isEmpty()) return [];
		$locale = $this->locale;
		foreach($cities as $city) {
			$citiesResult[] = ['name' => $city->$locale, 'id' => $city->id];
		}
		return $citiesResult;
	}

	/**
	 * Get all vehicles
	 * GET /vehicle-types
	 *
	 * @return Response
	 */
	public function index() {
		$locale = in_array(Request::header('Locale'), $this->avaibleLocales) ? Request::header('Locale') : $this->avaibleLocales[0];
		$vehicleTypes = VehicleTypeRef::with('bodyTypes')->get();

		foreach ($vehicleTypes as $vehicleType) {

			foreach($vehicleType->bodyTypes as $bodyType)
				$bodyTypesResult[] = ['name' => $bodyType->$locale, 'id' => $bodyType->id];

			$result[] = [
				'name' => $vehicleType->$locale,
				'id' => $vehicleType->id,
				'body-types' => $bodyTypesResult
			];

		}

		return $this->respond($result);
	}

}