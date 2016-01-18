<?php

class Like extends Eloquent {

	protected $fillable = ['likeable_id', 'likeable_type', 'user_id', 'created_at'];

	protected $hidden = ['user_id', 'likeable_id', 'likeable_type', 'created_at'];

	protected $appends = ['postable'];

	public $timestamps = false;

	public function getDates() {
		return ['created_at'];
	}

	public function likeable() {
		return $this->morphTo();
	}

	public function toArray() {
//		$response = [];
//
		if ($this->attributes['likeable_type'] == 'Post')
			return $this->post;
		else
			return $this->comment;

//		$response['id'] = $this->attributes['id'];

//		return $response;
	}

	public function comment() {
		return $this->belongsTo('Comment', 'likeable_id');
	}

	public function post() {
		return $this->belongsTo('Post', 'likeable_id');
	}

	public function user() {
		return $this->belongsTo('User');
	}

	public static function usersIDs($type, $id) {
		return Like::where('likeable_type', $type)->where('likeable_id', $id)->get()->map(function($like) {
			return $like->user_id;
		});
	}
}