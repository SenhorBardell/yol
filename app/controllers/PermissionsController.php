<?php

class PermissionsController extends ApiController {

	/**
	 * Display a listing of the resource.
	 * GET /permissions
	 *
	 * @return Response
	 */
	public function index() {
		return Permission::all()->toJson();
	}


	/**
	 * Assign permission to role
	 * GET /permissions/{id}/assign/{role_id}
	 *
	 * @param int $id
	 * @param int $role_id
	 * @return Response
	 */
	public function assign($id, $role_id) {
		$role = Role::find($role_id);

		if (!$role->exists())
			return $this->respondNotFond('Role not found');

		$permission = Permission::find($id);

		if (!$permission->exists())
			return $this->respondNotFond('Permission not found');

		foreach ($role->permissions as $currentPermission)
			if ($permission->id == $currentPermission->id)
				return $this->respondServerError('Permission is already in role');

		$role->permissions()->attach($permission->id);
		
		return $this->respondNoContent();
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /permissions
	 *
	 * @return Response
	 */
	public function store() {
		$permission = Permission::create(Input::all());

		if ($permission)
			return $this->respond($permission);

		return $this->respondServerError();
	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}/permissions
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id) {

		$user = User::find($id);

		if (is_null($user))
			return $this->respondNotFond('User not found');

		$response = $user->roles()->with('permissions')->get();

		if ($response->isEmpty())
			return $this->respondNotFond('User have no permissions');

		return $this->respond($response);
	}

	/**
	 * Update the specified resource in storage.
	 * PATCH /permissions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {

		$permission = Permission::find($id);

		$permission->fill(Input::all());

		if ($permission->save())
			return $this->respond($permission);

		return $this->respondServerError();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /permissions/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		$permission = Permission::find($id);

		if ($permission->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

}