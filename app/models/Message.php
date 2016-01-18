<?php

class Message extends \Eloquent {
    protected $table = 'messages';
	// Wrong model initialization, there is $model = new Model($data) in code, just for view sake.
	// Because of it i forced to include all fillable, including timestamps
    protected $fillable = [
		'id', 'chat_id', 'user_id', 'timestamp',
		'text', 'image_id', 'car_id', 'car_number', 'lat', 'lng', 'location',
		'delivered_at', 'viewed_at'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getTimestamp() {
        return $this->timestamp ? strtotime($this->timestamp) : $this->timestamp;
    }

	public function chat() {
		return $this->belongsTo('Chat');
	}

	public function getDates() {
		return ['delivered_at'];
	}

	public function getDeliveredAtAttribute($value) {
		return $value ? strtotime($value) : null;
//		if ($value) {
//			$value = new DateTime($value);
//			return $value->getTimestamp();
//		}
//		return $value;
	}

	public function getViewedAtAttribute($value) {
		return $value ? strtotime($value) : null;
	}

    public function getAsArray($redundantly = false) {
        if(isset($this->id) && (int)$this->id > 0) {
            $content = array();

            if(!is_null($this->text)) {
                $content['text'] = $this->text;
            } else if(!is_null($this->image_id)) {
                $domain = 'http' . (Request::server('HTTPS') ? '' : 's') . '://' . Request::server('HTTP_HOST');

                $attachment = MessageAttachment::find($this->image_id);

                $content['image'] = array(
                    'id' => (int)$attachment->id,
                    'thumb' => $domain . '/api/messages/attach/thumb/' . $attachment->id,
                    'origin' => $domain . '/api/messages/attach/gallery/' . $attachment->id,
                    'width' => $attachment->width,
                    'height' => $attachment->height
                );
            } else if(!is_null($this->car_id)) {
                $car = Car::withTrashed()->find($this->car_id);

                $content['car'] = array(
                    'id' => (int)$car->id,
                    'mark' => (int)$car->mark,
                    'model' => (int)$car->model,
                    'year' => (int)$car->year,
                    'color' => (int)$car->color,
                    'vehicle_type' => (int)$car->vehicle_type,
                    'body_type' => (int)$car->body_type
                );

                if(!is_null($this->car_number)) {
                    $content['car']['number'] = $this->car_number;
                }
            } else if(!is_null($this->lat)) {
                $content['geo'] = array(
                    'lat' => (double)$this->lat,
                    'long' => (double)$this->lng,
                    'location' => $this->location
                );
            }

            if($redundantly) {
                $user = User::find($this->user_id);

                return array(
                    'message_id' => (int)$this->id,
                    'chat_id' => (int)$this->chat_id,
                    'user' => array(
                        'id' => (int)$user->id,
                        'name' => $user->name,
                        'img' => array(
                            'middle' => $user->img_middle
                        )
                    ),
                    'content' => $content,
                    'timestamp' => $this->getTimestamp(),
					'delivered_at' => $this->delivered_at,
					'viewed_at' => $this->viewed_at
                );
            } else {
                return array(
                    'message_id' => (int)$this->id,
                    'chat_id' => (int)$this->chat_id,
                    'user_id' => (int)$this->user_id,
                    'content' => $content,
                    'timestamp' => $this->getTimestamp(),
					'delivered_at' => $this->delivered_at,
					'viewed_at' => $this->viewed_at
                );
            }
        }

        return array();
    }
}