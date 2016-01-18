<?php

class State extends \Eloquent {
    protected $table = 'states';
    protected $fillable = ['id', 'object', 'object_id', 'subject_id', 'owner_id', 'event', 'user_id', 'timestamp'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getTimestamp() {
        return $this->timestamp ? strtotime($this->timestamp) : $this->timestamp;
    }
}

