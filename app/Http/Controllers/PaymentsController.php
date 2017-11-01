<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Payout;
use Stripe\Subscription;
use Stripe\Customer;
use Illuminate\Support\Facades\Config;


class PaymentsController extends Controller
{

    public function __construct()
    {

       Stripe::setApiKey(Config::get('stripe.stripe_secret'));

    }


    public function index()
    {

        $customer = Customer::create(array(
            "email" => "example@email.com",
//            "source" => "src_18eYalAHEMiOZZp1l9ZTjSU0",
        ));

//        Sources that can be reused have their usage parameter set to reusable.


        Subscription::create(array(
            "customer" => $customer->id,
            "items" => array(
                array(
                    "plan" => "GBP_unit",
                ),
            )
        ));
    }



    public function create()
    {


    }



    public function store(Request $request)
    {
        //
    }



    public function show($id)
    {
        //
    }



    public function edit($id)
    {
        //
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }
}
