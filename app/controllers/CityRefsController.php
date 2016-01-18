<?php

class CityRefsController extends ApiController {

	public $avaibleLocales = ['ru', 'az'];

	/**
	 * Display a listing of the resource.
	 * GET /cities
	 *
	 * @return Response
	 */
	public function index()	{
		$locale = in_array(Request::header('Locale'), $this->avaibleLocales) ? Request::header('Locale') : 'ru';
		$cities = CityRef::all();

		foreach ($cities as $city)
			$result[] = ['name' => $city->$locale, 'id' => $city->id];


	    return $this->respond($result);
	}

	/**
	 * Store a newly created resource in storage.
	 * PUT /cityrefs
	 *
	 * @return Response
	 */
	public function store() {
		return CityRef::create(Input::all());
	}


	/**
	 * Update the specified resource in storage.
	 * PATCH /cityrefs/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$city = CityRef::find($id);

        if (!$city)
            return $this->respondNotFound('City not found');

        if ($city->fill(Input::all()))
            return $city;

        return $this->respondServerError('Error updating city');
	}

}