<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Love extends Model
{
    public function user()
    {
        $this->belongsTo('App\User');
    }

    public function track()
    {
        $this->belongsTo('App\Track');
    }
}
