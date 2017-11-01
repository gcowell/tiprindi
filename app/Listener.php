<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Stripe\Stripe;
use Stripe\Payout;
use Stripe\Subscription;
use Stripe\Customer;
use Stripe\InvoiceItem;
use Illuminate\Support\Facades\Config;

class Listener extends Model
{

    protected $fillable =
        [
            'user_id',
            'currency',
            'like_value',
            'love_value',
            'stripe_token',
            'stripe_customer_id'
        ];

    protected $hidden = ['stripe_token', 'stripe_customer_id'];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($attributes = array())
    {
        parent::__construct($attributes); // Eloquent

        Stripe::setApiKey(Config::get('stripe.stripe_secret'));
    }


    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function likes()
    {
        return $this->hasMany('App\Like');
    }


    public function loves()
    {
        return $this->hasMany('App\Love');
    }


    public function createStripeCustomer()
    {
        $customer = Customer::create(array(
            "email" => $this->user->email,
            "source" => $this->payment_token,
        ));

        $this->stripe_customer_id = $customer->id;
        $this->save();

        return true;
    }


    public function applyStripeSubscription()
    {
        Subscription::create(array(
            "customer" => $this->stripe_customer_id,
            "plan" => "basic",
        ));

        return true;
    }


    public function addLikeCharge()
    {
        Invoiceitem::create(array(
            "customer" => $this->stripe_customer_id,
            "amount" => $this->like_value,
            "currency" => "gbp",
            "description" => "Liked Track xxxx",
        ));
    }



    public function addLoveCharge()
    {
        Invoiceitem::create(array(
            "customer" => $this->stripe_customer_id,
            "amount" => $this->love_value,
            "currency" => "gbp",
            "description" => "Loved Track xxxx",
        ));
    }

}
