<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Artist;
use App\Http\Requests\ArtistRequest;

class ArtistsController extends Controller
{


    public function index()
    {

    }


    public function create()
    {

        return view('artists.create');

    }


    public function store(ArtistRequest $request)
    {
        $user_id = Auth::user()->id;
        $artist = Artist::create(array_merge($request->all(), ['user_id' => $user_id]));

        $artist->createStripeAccount($request);
        $artist->addStripeLegalDocument($request);

        return redirect('/users/dashboard');
    }


    public function show($id)
    {
        $artist = Artist::findOrFail($id);

        return view('artists/show')->with(['artist' => $artist]);
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
