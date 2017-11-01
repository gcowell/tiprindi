<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\Charge;
use Stripe\FileUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class Artist extends Model
{

    protected $fillable =
        [
            'user_id',
            'currency',
            'artist_name',
            'bank_info',
            'first_name',
            'last_name',
            'dob',
            'line1',
            'city',
            'postal_code',
            'stripe_token'
        ];

    protected $hidden = ['stripe_id', 'stripe_token'];

    protected $dates = ['dob'];

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function __construct($attributes = array())
    {
        parent::__construct($attributes); // Eloquent

        Stripe::setApiKey(Config::get('stripe.stripe_secret'));
    }

    //RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function releases()
    {
        return $this->hasMany('App\Release');
    }

    public function tracks()
    {
        return $this->hasMany('App\Track');
    }


    public function createStripeAccount($request)
    {
        $stripeAccount = Account::create(array(
            "country" => "GB",
            "type" => "custom",
            "external_account" => $request->account_token,
            "legal_entity" =>
            [
                "type" => "individual",
                "dob" =>
                     [
                         "day" => 28,
                         "month" => 10,
                         "year" => 1987,
                     ],
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "address" =>
                [
                    "city" => $request->city,
                    "line1" => $request->line1,
                    "postal_code" => $request->postal_code,
                ]
            ],
            "tos_acceptance" =>
            [
                "date" => time(),
                "ip" => $request->ip()
            ]

        ));

        $this->stripe_id = $stripeAccount->id;
        $this->save();

        return true;
    }



    public function sendPayment()
    {

        $charge = Charge::create(array(
            "amount" => 10,
            "currency" => "gbp",
            "source" => "tok_visa",
            "destination" => array(
                "amount" => 9,
                "account" => $this->stripe_id,
            ),
        ));
    }

    public function addStripeLegalDocument($request)
    {
        $documentFile = $request->file('legal_document');
        $documentPath = $documentFile->getPathName();

        $stripeAccount = Account::retrieve($this->stripe_id);

        $document = FileUpload::create
            (
            [
                "purpose" => "identity_document",
                "file" => fopen($documentPath, 'r')
            ],
            [
                "stripe_account" => $this->stripe_id
            ]
        );

        $stripeAccount->legal_entity->verification->document = $document->id;
        $stripeAccount->save();

        return true;
    }

}
