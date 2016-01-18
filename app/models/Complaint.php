<?php

class Complaint extends \Eloquent {
    protected $table = 'complaints';
    protected $fillable = ['id', 'owner_id', 'post_id', 'user_id', 'comment_id', 'type', 'timestamp'];
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function getTimestamp() {
        return $this->timestamp ? strtotime($this->timestamp) : $this->timestamp;
    }
}