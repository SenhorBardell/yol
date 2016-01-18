<?php

use Helpers\Transformers\CollectionTransformer;

class CarsController extends ApiController {

	/**
	 * @var Helpers\Transformers\CollectionTransformer
	 */
	protected $collectionTransformer;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->collectionTransformer = $collectionTransformer;
	}

	/**
	 * Display all cars
	 * GET /api/users/{id}/cars
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function index($id) {

		$user = User::find($id);

		if (!$user)
			return $this->respondNotFound('User not found');

		$cars = $user->cars;

		if ($cars->count())
			return $this->respond($this->collectionTransformer->transformCars($cars));

		return $this->respondNotFound('No cars');
	}

	public function selfIndex() {
		$user = Auth::user();

		$cars = $user->cars;

		if ($cars->isEmpty())
			return $this->respondNotFound('No cars');

		$cars->load('images');

		return $this->respond($this->collectionTransformer->transformCars($cars));
	}

	/**
	 * Get all cars of myself
	 * GET /api/users/self/cars
	 *
	 * @return Response
	 */
	public function selfCars() {
		$user = Auth::user();

		$cars = $user->cars();

		if ($cars->exists())
			return $this->respond($this->collectionTransformer->transformCars($cars));

		return $this->respondNotFound('No cars');
	}

	/**
	 * Store a new car
	 * POST /api/users/self/cars
	 *
	 * @return Response
	 */
	public function selfStore() {
		$user = Auth::user();

//		if (!Input::has('number'))
//			return $this->respondInsufficientPrivileges('Please select number');

		$validator = Car::validate(Input::all());

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		$number = strtoupper(Input::get('number'));

//		if ($number[2] == 'Z' && $number[3] == 'Z')
//			return $this->respondInsufficientPrivileges('Wrong number. There is no ZZ');

		$region = substr($number, 0, 2);

		if (is_numeric($number[0]) && (int)Input::get('vehicle_type') != 3) {
			// smells a bit but whatever
			switch ((int)$region) {
				case $region == 0:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
				case $region >= 73 && $region <= 84:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
				case $region >= 86 && $region <= 89:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
				case $region >= 91 && $region <= 98:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
			}
		}

		if (is_numeric($number[0]) && strlen($number) > 6) {
			$zerosCount = 3; // amount of zeros
			// check if all numbers are zeros
			$zeros = array_diff(array_slice(str_split($number), 4), array_fill(0, $zerosCount, 0));
			if (empty($zeros)) return $this->respondInsufficientPrivileges("Номер не может быть с {$zerosCount} нулями");
		} elseif (strlen($number) > 6) {
			$zerosCount = 6;
			$zeros = array_diff(array_slice(str_split($number), 1), array_fill(0, $zerosCount, 0));
			if (empty($zeros)) return $this->respondInsufficientPrivileges("Номер не может быть с {$zerosCount} нулями");
		}

		// if seria in number are in AA, PA, РМ, YP. Which is not allowed.
		if (Car::checkSeria($number)) {
			return $this->respondInsufficientPrivileges('Номера с сериями АА, РА, РМ и YP добавляются в профиль пользователя службой поддержки только при подтверждении факта владения или пользования данной машиной. Обратитесь в техподдержку.');
		}

		$car = new Car(Input::all());

		$mark = MarkRef::find($car->mark);
		$model = ModelRef::find($car->model);

		$vehicleType = VehicleTypeRef::find($car->vehicle_type);

		$this->subscribe($user, "{$this->morphWord($vehicleType)} {$mark->name}");

		$this->subscribe($user, $mark->name.' '.$model->name);

		if ($user->cars()->save($car)) {

			$imgCollection = Image::findMany(Input::get('img'));

			foreach ($imgCollection as $imgObject) {
				$car->images()->save($imgObject);
			}

			return $this->respond($this->collectionTransformer->transformCar($car));
		}

		return $this->respondServerError();
	}

	private function morphWord($vehicleType) {
		switch ($vehicleType) {
			case $vehicleType->id == 1:
				return $vehicleType->ru.'и';
				break;
			case $vehicleType->id == 4:
				return $vehicleType->ru.'ы';
				break;
			default:
				return $vehicleType->ru;
				break;
		}
	}

	/**
	 * Store in database
	 * POST /api/users/{id}/cars
	 *
	 * @param int $id
	 *
	 * @return Response
	 */
	public function store($id) {

		$user = User::find($id);

		if (!$user)
			return $this->respondNotFound('user not found');

		$car = new Car(Input::all());

		if ($user->cars()->save($car)) {

			if (Input::has('img')) {
				$car->images()->attach(Input::get('img'));
			}

			return $this->respond($this->collectionTransformer->transformCar($car));
		}

		return $this->respondServerError();
	}

	/**
	 * Show single car
	 * GET /api/users/{id}/cars/{id}
	 *
	 * @param  int $id
	 * @param int $car_id
	 * @return Response
	 */
	public function show($id, $car_id) {

		$user = User::find($id);

		if (!$user)
			return $this->respondNotFound('User not found');

		$car = $user->cars()->find($car_id);

		if ($car)
			return $this->respond($this->collectionTransformer->transformCar($car));

		return $this->respondNotFound('Car not found');
	}

	/**
	 * Change primary car for user
	 * GET /api/users/self/change-car/{id}
	 *
	 * @param int $id
	 * @return Response
	 */
	public function makePrimary($id) {
		$user = Auth::user();

		$car = $user->cars()->find($id);

		if (!$car)
			return $this->respondNotFound('Car not found');

		$user->car_id = $id;

		if ($user->save())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	/**
	 * Update a car
	 * PATCH /api/user/{id}/car/{id}
	 *
	 * @param  int $id
	 * @param int $car_id
	 * @return Response
	 */
	public function update($id, $car_id) {
		$validator = Car::validate(Input::all());
		$idValidator = $this->validateId($id);
		$carIdvalidator = $this->validateId($car_id);

		if ($validator->fails())
			return $this->respondInsufficientPrivileges($validator->messages()->all());

		if ($idValidator->fails())
			return $this->respondInsufficientPrivileges($idValidator->messages()->all());

		if ($carIdvalidator->fails())
			return $this->respondInsufficientPrivileges($carIdvalidator->messages()->all());

		$user = User::find($id);

		if (!$user)
			return $this->respondNotFound('User not found');

		$car = $user->cars()->find($car_id);

		if (!$car)
			return $this->respondNotFound('Car or User not found');

		if ($car->update(Input::all()))
			return $this->respond($this->collectionTransformer->transformCar($car));

		return $this->respondServerError();
	}

	/**
	 * Update car from self list
	 * @param $id
	 * @return Response
	 */
	public function selfUpdate($id) {
		$user = Auth::user();

		$car = $user->cars()->find($id);

		if (!$car)
			return $this->respondNotFound('Car not found');

		if (Input::get('number') != $car->number) {
			$validator = Car::validate(Input::all());

			if ($validator->fails())
				return $this->respondInsufficientPrivileges($validator->messages()->all());
		}

		$number = Input::get('number');

		if ($number[2] == 'Z' && $number[3] == 'Z')
			return $this->respondInsufficientPrivileges('Wrong number. There is no ZZ');

		$region = substr($number, 0, 2);

		if (is_numeric($number[0]) && (int)Input::get('vehicle_type') != 3) {
			switch ((int)$region) {
				case $region == 0:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
				case $region >= 73 && $region <= 84:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
				case $region >= 86 && $region <= 89:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
				case $region >= 91 && $region <= 98:
					return $this->respondInsufficientPrivileges('Недопустимый код региона');
			}
		}

		if (is_numeric($number[0]) && strlen($number) > 6) {
			$zerosCount = 3; // amount of zeros
			// check if all numbers are zeros
			$zeros = array_diff(array_slice(str_split($number), 4), array_fill(0, $zerosCount, 0));
			if (empty($zeros)) return $this->respondInsufficientPrivileges("Номер не может быть с {$zerosCount} нулями");
		} elseif (strlen($number) > 6) {
			$zerosCount = 6;
			$zeros = array_diff(array_slice(str_split($number), 1), array_fill(0, $zerosCount, 0));
			if (empty($zeros)) return $this->respondInsufficientPrivileges("Номер не может быть с {$zerosCount} нулями");
		}

		// if seria in number are in AA, PA, РМ, YP. Which is not allowed.
		if (Car::checkSeria($number)) {
			return $this->respondInsufficientPrivileges('Номера с сериями АА, РА, РМ и YP добавляются в профиль пользователя службой поддержки только при подтверждении факта владения или пользования данной машиной. Обратитесь в техподдержку.');
		}

		$car->fill(Input::all());

		if ($car->save()) {

			$imgs = Image::findMany(Input::get('img'))->map(function ($image) use($car) {
				if (!$car->images()->find($image->id)) {
					$car->images()->save($image);
					return $image->id;
				}

				if ($car->images()->find($image->id)) return $image->id;
			});

			if (!$imgs->isEmpty())
				$car->images()->whereNotIn('id', $imgs->toArray())->delete();
			else {
				$car->images()->delete();
			}

			return $this->respond($this->collectionTransformer->transformCar($car));
		}

		return $this->respondServerError();
	}

	/**
	 * Delete a car from database
	 * DELETE /api/user/{id}/car/{id}
	 *
	 * @param  int $id
	 * @param int $car_id
	 * @return Response
	 */
	public function destroy($id, $car_id) {
		$idValidator = $this->validateId($id);
		$carIdvalidator = $this->validateId($car_id);

		if ($idValidator->fails())
			return $this->respondInsufficientPrivileges($idValidator->messages()->all());

		if ($carIdvalidator->fails())
			return $this->respondInsufficientPrivileges($carIdvalidator->messages()->all());

		$user = User::find($id);

		if (!$user)
			return $this->respondNotFound('User not found');

		$car = $user->cars()->find($car_id);

		if (!$car)
			return $this->respondNotFound('Car not found');

		if ($car->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	/**
	 * Delete car from my list
	 * @param $id car id
	 *
	 * @return Response
	 */
	public function selfDestroy($id) {
		$user = Auth::user();

		$car = $user->cars()->find($id);

		if (!$car)
			return $this->respondNotFound('Car not found');

		if ($car->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	public function destroyImage($id, $image_id) {
		$user = Auth::user();

		$car = $user->cars()->find($id);

		if (!$car)
			return $this->respondNotFound('Car not found');

		$image = $car->images()->find($image_id);

		if ($image->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

	/**
	 * Subscribe to category with provided title
	 *
	 * @param $user
	 * @param $title
	 */
	private function subscribe(\User $user, $title) {
		if (!$user->subscriptions()->whereRaw("LOWER(title) = LOWER(?)", [$title])->first())
			if ($category = Category::whereRaw("LOWER(title) = LOWER(?)", [$title])->first())
				$user->subscriptions()->attach($category->id);
	}
}