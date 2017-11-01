<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Track extends Model
{

    protected $fillable = ['release_id', 'track_title', 'track_number', 'file_location', 'original_filename', 'upload_id', 'file_type'];



    //RELATIONSHIPS
    public function release()
    {
        return $this->belongsTo('App\Release');
    }

    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }

    public function likes()
    {
        $this->hasMany('App\Like');
    }

    public function loves()
    {
        $this->hasMany('App\Love');
    }

    public function markConversionComplete()
    {
        $this->conversion_complete = true;
        $this->save();
    }

    public function hello()
    {

    }


}
