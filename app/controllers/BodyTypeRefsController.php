<?php

class BodyTypeRefsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 * GET /body-types
	 *
	 * @return Response
	 */
	public function index() {
		$bodies = BodyTypeRef::paginate();
		return View::make('admin.dicts.list', [
			'columns' => ['ID', 'Ru', 'AZ', 'Действия'],
			'data' => $bodies->transform(function ($b) {
				return [
					'id' => $b->id,
					'ru' => $b->ru,
					'az' => $b->az,
					'edit' => link_to_action('BodyTypeRefsController@edit', 'редактировать &rarr;', [$b->id])
				];
			}),
			'links' => $bodies->links(),
			'title' => 'Типы кузова',
			'actions' => [
				['link' => '/admin/body-types/create', 'text' => 'Добавить']
			]
		]);
	}

	public function edit($id) {
		return View::make('admin.body-types.edit', ['bodyType' => BodyTypeRef::find($id)]);
	}

	/**
	 * Store a newly created resource in storage.
	 * PUT /body-types
	 *
	 * @return Response
	 */
	public function store() {
		return BodyTypeRef::create(Input::all());
	}

	/**
	 * Display the specified resource.
	 * GET /body-types/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {
		$bodyType = BodyTypeRef::find($id);

		if (!$bodyType)
			return $this->respondNotFound('Body type not found');

		return $bodyType;
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH /body-types/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$bodyType = BodyTypeRef::find($id);

		if (!$bodyType)
			return $this->respondNotFound('Body type not found');

		if ($bodyType->fill(Input::all()))
			return $bodyType;

		return $this->respondServerError();
	}

}