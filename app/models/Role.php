<?php 

/**
 * Role
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Permission[] $permissions
 */
class Role extends Eloquent {
	
	protected $fillable = ['name'];

	public $timestamps = false;

	public function permissions() {
		return $this->belongsToMany('Permission');
	}

}