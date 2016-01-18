<?php

use Helpers\GenerationUtils\Generator;

class MarkRefsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 * GET /marks
	 *
	 * @return Response
	 */
	public function index() {
        $marks = MarkRef::orderBy('id')->paginate();
        $links = $marks->links();
        $marks = $marks->transform(function($mark) {
            return [
                'id' => $mark->id,
                'name' => $mark->name,
                'models' => link_to("admin/marks/{$mark->id}/models", 'модели &rarr;'),
                'edit' => link_to("admin/marks/{$mark->id}/edit", 'Редактировать')
            ];
        });
		return View::make('admin.dicts.list', [
            'columns' => ['ID', 'Название', 'Модели', 'Действие'],
            'data' => $marks,
            'links' => $links,
            'title' => 'Марки',
            'actions' => [
                ['link' => '/admin/marks/create', 'text' => 'Добавить']
            ]
        ]);
	}

    public function edit($id) {
        $mark = MarkRef::with('vehicleTypes')->find($id);
        $vehicles = VehicleTypeRef::all()->transform(function ($vehicle) use ($mark) {
            return [
                'exists' => $mark->vehicleTypes->filter(function ($v) use ($vehicle) {
                    return $v->id == $vehicle->id;
                })->count() != 0,
                'id' => $vehicle->id,
                'ru' => $vehicle->ru
            ];
        });

        return View::make('admin.marks.edit', [
            'mark' => $mark,
            'vehicles' => $vehicles,
            'title' => 'Редактировать марку'
        ]);
    }

    public function create() {
        return View::make('admin.marks.create', [
            'vehicles' => VehicleTypeRef::all()->transform(function ($vehicle) {
                return [
                    'exists' => false,
                    'id' => $vehicle->id,
                    'ru' => $vehicle->ru
                ];
            }),
            'title' => 'Создать марку'
        ]);
    }

	/**
	 * Store a newly created resource in storage.
	 * PUT /marks
	 *
	 * @return Response
	 */
    public function store() {
        $mark = new MarkRef(Input::only('name'));
        Input::has('vehicle_type') ?: $mark->vehicle_type_id = Input::get('vehicle_type');
        if ($mark->save()) {
            $category = Generator::findOrCreate($mark->name);
            if ($category)
                Generator::findOrCreate("Все о {$mark->name}", null, $category->id);

            return Redirect::action('MarkRefsController@index');
        }
        return Redirect::action('MarkRefsController@index');
    }

    /**
     * Show all models from mark by id
     * GET /marks/{id}/models
     *
     * @param $id
     * @return Response
     */
    public function show($id) {
        $mark = MarkRef::with('models')->find($id);

        if (!$mark)
            return $this->respondNotFound('Mark not found');

        return $mark;
    }

    /**
     * Show mark
     * GET /marks/{id}
     *
     * @param $id
     * @return Response
     */
    public function single($id) {
        $mark = MarkRef::find($id);

        if (!$mark)
            return $this->respondNotFound('Mark not found');

        return $mark;
    }

	/**
	 * Update the specified resource in storage.
	 * PATCH /marks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
        $mark = MarkRef::find($id);

        if (!$mark)
            return $this->respondNotFound('Mark not found');

        if (Input::has('vehicle_type')) {
            $mark->vehicleTypes()->detach();
            $mark->vehicleTypes()->attach(Input::get('vehicle_type'));
        }

        $mark->fill(Input::except('vehicle_type'));

        if ($mark->save())
            return Redirect::action('MarkRefsController@index');

        return App::abort('Wrong params');
	}

}