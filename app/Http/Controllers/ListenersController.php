<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ListenerRequest;
use Illuminate\Support\Facades\Auth;
use App\Listener;
use Illuminate\Support\Facades\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ListenersController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        if (Auth::user()->isListener())
        {
            return redirect('/users/dashboard');
        }
        else
        {
        return view('listeners.create');
        }
    }


    public function store(ListenerRequest $request)
    {
        $user_id = Auth::user()->id;
        $listener = Listener::create(array_merge($request->all(), ['user_id' => $user_id]));

        $listener->createStripeCustomer();
        $listener->applyStripeSubscription();

        return redirect('/users/dashboard');
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

    public function test()
    {
        $listener = Auth::user()->listener;
        $listener->createInvoice();
    }

}
