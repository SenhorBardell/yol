<?php 

/**
 * Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Role[] $roles
 */
class Permission extends Eloquent {
	
	protected $fillable = ['action'];

	public $timestamps = false;

	public static function validate($input) {
		$rules = ['action' => 'alpha_spaces'];

		return Validator::make($input, $rules);
	}

	public function roles() {
		return $this->belongsToMany('Role');
	}

}