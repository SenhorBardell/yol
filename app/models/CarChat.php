<?php

use Illuminate\Database\Eloquent\Collection;

/**
 * Class CarChat
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|CarChatMessageAttachment $attachments
 * @property-read \Illuminate\Database\Eloquent\Collection|CarMessage $messages
 */
class CarChat extends Eloquent {


	protected $fillable = ['owner_id', 'receiver_id', 'receiver_car_id', 'number', 'byNumber'];

	public function scopeByNumber($query, $number) {
		return $query->where('number', $number);
	}

	public function scopeByUserId($query, $id) {
		return $query->omitRemoved($id)->where('owner_id', $id)->orWhere('receiver_id', $id);
	}

	public function scopeBylastId($query, $id) {
		return $query->where('id', '<', $id);
	}

	public function scopeOmitRemoved($query, $id) {
		if ($this->isOwner($id))
			return $query->whereNull('deleted_by_owner');
		else
			return $query->whereNull('deleted_by_receiver');
	}

	public function owner() {
		return $this->belongsTo('User', 'owner_id')->withTrashed();
	}

	public function receiver() {
		return $this->belongsTo('User', 'receiver_id')->withTrashed();
	}

	public function receiverCar() {
		return $this->belongsTo('Car', 'receiver_car_id')->withTrashed();
	}

	public function messages() {
		return $this->hasMany('CarMessage', 'chat_id');
	}

	public function lastMessage() {
		return $this->belongsTo('CarMessage', 'last_message_id');
	}

	public function attachments() {
		return $this->hasMany('MessageAttachment', 'chat_id');
	}

	public function isUnread($userId) {
		if (!$this->lastMessage) return false;

		return $this->lastMessage->user_id != $userId && !$this->lastMessage->viewed_at;
	}

	public function isOwner($id) {
		return $this->owner_id == $id;
	}

	public function isMember($id) {
		return $this->isOwner($id) || $this->receiver_id == $id;
	}

	public function getCreatedAtAttribute($value) {
		return strtotime($value);
	}

	public function getUpdatedAtAttribute($value) {
		return strtotime($value);
	}

	public function getMembersTokens($excludeId = null) {
		$devices = new Collection();
		if ($excludeId != $this->owner->id) $devices = $devices->merge($this->owner->devices);
		if ($excludeId != $this->receiver->id) $devices = $devices->merge($this->receiver->devices);
		return $devices;
	}
}