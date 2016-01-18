<?php

class Attachment extends Eloquent {

	protected $fillable = ['attachable_id', 'attachable_type'];

	public function attachments() {
		return $this->morphToMany('Attachment', 'attachable');
	}
}