<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['email', 'password', 'remember_token'];


    public function isArtist()
    {
        if($this->hasOne('App\Artist')->count())
        {
            return true;
        }
        else
        {
            return false;
        }
    }



    public function isListener()
    {
        if($this->hasOne('App\Listener')->count())
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    public function artist()
    {
        return $this->hasMany('App\Artist');

    }


    public function listener()
    {
        return $this->hasOne('App\Listener');
    }


    public function isUnassociated()
    {
        if (!$this->isArtist() && !$this->isListener())
        {
            return true;
        }
        else
        {
            return false;
        }
    }





}
