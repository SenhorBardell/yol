<?php

class RolesController extends ApiController {

	/**
	 * Display all roles
	 * GET api/roles
	 * 
	 * @return Response
	 */
	public function index() {
		return Role::all()->toJson();
	}

	/**
	 * Store new role
	 * POST api/roles
	 * 
	 * @return Response
	 */
	public function store() {
		$role = Role::create(Input::all());

		return $role->toJson();
	}

	/**
	 * Grant user specific roles
	 * GET /api/users/{id}/grant/{role_id}
	 * 
	 * @param  int $id
	 * @param  int $role_id
	 * @return Response
	 */
	public function grant($id, $role_id) {
		$user = User::find($id);

		if (!is_object($user))
			return $this->respondNotFound('User not found');

		$role = Role::find($role_id);

		if (!is_object($role))
			return $this->respondNotFound('Role not found');

		foreach ($user->roles as $currentRole)
			if ($role->id == $currentRole->id)
				return $this->respondServerError('User already have this role');

		$user->roles()->attach($role->id);

		return $this->respondNoContent();
	}

	/**
	 * Show permissions of this role
	 * GET /api/roles/{id}/permissions
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($id) {
		$role = Role::find($id);

		return $role->permissions->toJson();
	}

	/**
	 * Update role
	 * PATCH /api/roles/{id}
	 * 
	 * @param int $id
	 * @return Response
	 */
	public function update($id) {
		$role = Role::find($id);

		$role->fill(Input::all());

		if ($user->save())
			return $role->toJson();
	}

	/**
	 * Delete role
	 * 
	 * @return Response
	 */
	public function destroy($id) {
		$role = Role::find($id);

		if ($role->delete())
			return $this->respondNoContent();

		return $this->respondServerError();
	}

}