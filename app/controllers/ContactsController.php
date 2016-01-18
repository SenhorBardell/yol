<?php

use Helpers\Transformers\CollectionTransformer;

class ContactsController extends ApiController {

	protected $collectionTransformer;
	private $user;

	function __construct(CollectionTransformer $collectionTransformer) {
		$this->user = Auth::user();
		$this->collectionTransformer = $collectionTransformer;
	}

	public function index() {
		return $this->respond($this->collectionTransformer->transformContacts($this->user->contacts()->with('cars')->get()));
	}

	public function store() {
		$user = User::find(Input::get('user_id'));

		if (!$user)
			return $this->respondNotFound('User not found');

		if ($this->user->contacts()->find($user->id))
			return $this->respond($this->collectionTransformer->transformContact($user));

		$this->user->contacts()->attach($user->id);

		return $this->respond($this->collectionTransformer->transformContact($user));
	}

	public function destroy($id) {
		$contact = $this->user->contacts->find($id);

		if (!$contact)
			return $this->respondNotFound('User not found');

		DB::statement('delete from contact_user where user_id = ? and contact_id = ?', [$this->user->id, $id]);

		return $this->respondNoContent();
	}

}