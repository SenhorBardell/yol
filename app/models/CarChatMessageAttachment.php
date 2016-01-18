<?php

class CarChatMessageAttachment extends Eloquent {
	protected $fillable = ['origin', 'thumb', 'width', 'height'];
	public $timestamps = false;
}