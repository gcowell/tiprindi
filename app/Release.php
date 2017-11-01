<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Release extends Model
{


    protected $fillable =  ['release_title', 'release_date', 'release_type', 'artist_id'];

    protected $dates = ['release_date'];


    //RELATIONSHIPS
    public function tracks()
    {
        return $this->hasMany('App\Track')->orderBy('track_number');
    }

    public function artist()
    {
        return $this->belongsTo('App\Artist');
    }


    public function hasTracks()
    {
        if($this->hasOne('App\Track')->count())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function alreadyHas($trackNumber)
    {
        $tracklist = $this->tracks();

        foreach ($tracklist as $track)
        {
            if ($track->track_number === $trackNumber)
            {
                return true;
            }
            else
            {
                continue;
            }
        }
    }


    public function getTrackByNumber($trackNumber)
    {
        return $this->tracks->where('track_number', '=', $trackNumber);
    }
}
