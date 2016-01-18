<?php

class VehicleTypeRefsController extends ApiController {

	public $avaibleLocales = ['ru', 'az'];

	/**
	 * Get all vehicles
	 * GET /vehicle-types
	 *
	 * @return Response
	 */
	public function index() {
		$data = VehicleTypeRef::paginate();
		return View::make('admin.dicts.list', [
			'columns' => ['ID','Название RU', 'Название AZ', 'Действия'],
			'data' => $data->transform(function ($v) {
				return [
					'id' => $v->id,
					'ru' => $v->ru,
					'az' => $v->az,
					'edit' => link_to_action(
						'VehicleTypeRefsController@edit',
						'Редактировать',
						[$v->id]
					)
				];
			}),
			'links' => $data->links(),
			'actions' => [
				['link' => '/vehicle-types/create', 'text' => 'Добавить']
			]
		]);
	}

	public function edit($id) {
		return View::make('admin.vehicle-types.edit', [
			'vehicleType' => VehicleTypeRef::find($id)
		]);
	}

	public function create() {
		return View::make('admin.vehicle-types.create');
	}

	/**
	 * Store a vehicle type
	 * PUT /vehicle-types
	 *
	 * @return Response
	 */
	public function store() {
		if (VehicleTypeRef::create(Input::all()))
			return Redirect::action('VehicleTypeRefsController@index');
	}

	/**
	 * Display one type
	 *
	 * @param $id
	 * @return Response
	 */
	public function show($id) {
		$type = VehicleTypeRef::find($id);

		if (!$type)
			return $this->respondNotFound('Vehicle type not found');

		return $type;
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH /vehicle-types/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$type = VehicleTypeRef::find($id);

		if (!$type)
			return Redirect::action('VehicleTypeRefsController@index');

		$type->fill(Input::all());
		if ($type->save())
			return Redirect::action('VehicleTypeRefsController@index');

		return Redirect::action('VehicleTypeRefsController@index');
	}

}