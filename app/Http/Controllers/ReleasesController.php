<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReleaseRequest;
use App\Release;
use App\Artist;

class ReleasesController extends Controller
{
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function index($id)
    {
        $artist = Artist::findOrFail($id);
        $releases = $artist->releases()->getResults();


        return view('releases.index')->with(['artist' => $artist, 'releases' => $releases ]);
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function create($id)
    {
        $artist = Artist::findOrFail($id);

        return view('releases.create')->with(['artist' => $artist]);
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function store(ReleaseRequest $request, $id)
    {

        $artist_id = $id;    //TODO additional check that user is artist
        Release::create(array_merge($request->all(), ['artist_id' => $artist_id]));

        return redirect('/releases/' . $artist_id);
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function show($id)
    {
        $release = Release::findOrFail($id);

        return view('releases.show')->with(['release' => $release]);

    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function edit($id)
    {
        $release = Release::findOrFail($id);

        return view('releases.edit')->with(['release' => $release]);
    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function update(Request $request, $id)
    {
        $release = Release::findOrFail($id);

        $release->update($request->all());

        return redirect('/releases/show/' . $id);

    }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function destroy($id)
    {
        //
    }
}
