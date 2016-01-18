<?php

use Helpers\GenerationUtils\Generator;

class ModelRefsController extends ApiController {

    public function index($markId) {
        $models = MarkRef::find($markId)->models()->with('vehicleTypes')->paginate();
        $links = $models->links();
        $models = $models->transform(function ($model) {
            return ['id' => $model->id, 'name' => $model->name, 'vehicle_types' => $model->vehcleTypes];
        });
        return View::make('admin.dicts.list', [
            'columns' => ['ID', 'Название', 'Тип Авто'],
            'data' => $models,
            'links' => $links,
            'title' => 'Модели',
            'actions' => [
                ['link' => '/admin/models/create', 'text' => 'Добавить']
            ]
        ]);
    }

    public function listAll() {
        $models = ModelRef::with(['marks', 'vehicleTypes'])->paginate();
        $links = $models->links();
        $vehicleTypes = VehicleTypeRef::all();
        $models = $models->transform(function ($model) use ($vehicleTypes) {
            return [
                'id' => $model->id,
                'name' => $model->name,
                'mark' => $model->marks->name,
                'vehicle_type' => $vehicleTypes->filter(function($vehicleType) use($model){
                    return $model->vehicle_type_id == $vehicleType->id;
                })->first()->name,
                'edit' => link_to_action('ModelRefsController@edit', 'редактировать &rarr;', [$model->marks->id, $model->id])
            ];
        });
        return View::make('admin.dicts.list', [
            'columns' => ['ID', 'Название', 'Марка', 'Тип Авто', 'Действия'],
            'data' => $models,
            'links' => $links,
            'title' => 'Модели',
            'actions' => [
                ['link' => '/admin/models/create', 'text' => 'Добавить']
            ]
        ]);
    }

    public function edit($markId, $modelId) {
        return View::make('admin.models.edit', [
            'model' => ModelRef::with('marks')->find($modelId),
            'vehicle_types' => VehicleTypeRef::all()->transform(function ($veh) {
                return ['id' => $veh->id, 'ru' => $veh->ru, 'exists' => false];
            }),
            'marks' => MarkRef::all()
        ]);
    }

    public function create() {
        return View::make('admin.models.create', [
            'vehicles' => VehicleTypeRef::all()->transform(function ($v) {
                return ['id' => $v->id, 'ru' => $v->ru, 'exists' => false];
            }),
            'marks' => MarkRef::all()
        ]);
    }

	/**
	 * Store a newly created resource in storage.
	 * PUT /marks/{id}/models
	 *
     * @param int $id
	 * @return Response
	 */
	public function store() {
//        dd(Input::all());
		$mark = MarkRef::find(Input::get('mark'));

        if (!$mark)
            return $this->respondNotFound('Mark not found');

        $model = new ModelRef(Input::all());
        $model->vehicle_type_id = Input::get('vehicle_type');

        if ($model = $mark->models()->save($model)) {
            Generator::findOrCreate($mark->name.' '.$model->name, 'Все что касается этой модели');

            return Redirect::action('ModelRefsController@listAll');
        }

        return $this->respondServerError('Error saving model');
	}


	/**
	 * Update the specified resource in storage.
	 * PATCH /models/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
        $model = ModelRef::find($id);

        if (!model)
            return $this->respondNoContent('Model not found');

        if ($model->fill(Input::all()))
            return $model;

        return $this->respondServerError('Error updating model');
	}

}