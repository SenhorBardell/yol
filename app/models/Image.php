<?php

use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * Image
 *
 * @property-read \User $user
 */
class Image extends Eloquent {

	use SoftDeletingTrait;
	public $timestamps = false;
	protected $fillable = ['thumbnail', 'regular', 'width', 'height'];
	protected $hidden = ['imageable_id', 'imageable_type'];

	public function user() {
		return $this->belongsTo('User');
	}

	public function getThumbnailAttribute($value) {
		return S3_PUBLIC.$value;
	}

	public function getRegularAttribute($value) {
		return S3_PUBLIC.$value;
	}

	public function getOriginAttribute($value) {
		return S3_PUBLIC.$value;
	}

	public function imageable() {
		return $this->morphTo();
	}

}