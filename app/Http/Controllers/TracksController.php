<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Release;
use App\Track;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TrackRequest;
use App\Jobs\ConvertTrack;
use Illuminate\Support\Facades\View;
use Pbmedia\LaravelFFMpeg\FFMpegFacade;
use FFMpeg\Format\Video\X264;

class TracksController extends Controller
{

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function index()
    {
        //
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function create($id)
    {
        $release = Release::findOrFail($id);

        return view('tracks.create')->with(['release' => $release]);
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function store(TrackRequest $request, $id)
    {

        $release_id = $id;
        $release = Release::findOrFail($release_id);

        $upload_id = $request->upload_id;

        $disk = Storage::disk('s3');
        $track_file = $request->file('file');
        $original_filename = $track_file->getClientOriginalName();

        $trackFileName = $upload_id . '.' . $track_file->getClientOriginalExtension();
        $trackFilePath = '/' . $release->artist->id  .'/' . $release_id;
        $trackFullLocation = $trackFilePath . '/' .  $trackFileName;

        $disk->put($trackFullLocation, fopen($track_file, 'r+'));

        Track::create(array_merge($request->except('track_file'),
            [
                'file_location' => $trackFullLocation,
                'release_id' => $release_id,
                'original_filename' => $original_filename,
                'file_type' => $track_file->getClientOriginalExtension()
            ]
        ));

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function finish($id)
    {
        //GET FORM INPUT
        $verificationTrackList = (Input::except('_token'));

        //GET RELEASE AND ARTIST INFO
        $release_id = $id;
        $release = Release::findOrFail($release_id);
        $artist_id = $release->artist->id;

        //GET THE CURRENT UPLOADED TRACKS
        $uploaded_tracks = $release->tracks;

        //DERIVE THE STORAGE FILE PATH
        $filePath = $artist_id . '/' . $release_id . '/';

        //INSTANTIATE STORAGE DISK
        $disk = Storage::disk('s3');

        //INSTANTIATE ARRAY TO RECORD CORRECTLY UPLOADED FILES
        $uploadedTrackIDs = [];

        foreach ($verificationTrackList as $verificationTrack)
        {
            //DECODE THE JSON INPUT
            $verificationTrackData = json_decode($verificationTrack);

            //THE DATA PROVIDED ON FORM COMPLETION IS CONSIDERED TO
            //SUPERSEDE ANY DATA ALREADY UPLOADED
            $correctTrackNumber = $verificationTrackData->trackNumber;
            $correctUploadID = $verificationTrackData->uploadID;
            $correctTrackTitle = $verificationTrackData->trackTitle;

            //FIND THE TRACK IN DB WHICH CORRESPONDS TO UPLOAD ID
            $correspondingTrack = $uploaded_tracks->where('upload_id', $correctUploadID)->first();

            //DERIVE NEW FILEPATH BASED UPON TRACK NUMBER
            $file_type = $correspondingTrack->file_type;
            $newFileLocation = $filePath . $correctTrackNumber . '.' . $file_type;

            //RENAME UPLOADED FILE TO CORRECT TRACK NUMBER
            $disk->move($correspondingTrack->file_location, $newFileLocation);

            //UPDATE DATABASE WITH LATEST DATA
            $correspondingTrack->track_number = $correctTrackNumber;
            $correspondingTrack->file_location = $newFileLocation;
            $correspondingTrack->track_title = $correctTrackTitle;
            $correspondingTrack->save();

            //GET PUT THE ID IN THE ARRAY
            array_push($uploadedTrackIDs, $correctUploadID);

            $track_id = $correspondingTrack->id;
            $conversion = (new ConvertTrack($newFileLocation, $filePath, $correctTrackNumber, $track_id));
            $this->dispatch($conversion);


        }

        //REMOVE SUPERFLUOUS FILES
        foreach ($uploaded_tracks as $track)
        {
            //IF THE ID IS NOT IN THE ARRAY
            if (!in_array($track->upload_id, $uploadedTrackIDs))
            {
                //DELETE FILE AND DB ENTRY
                $disk->delete($track->file_location);
                $track->delete();
            }
            else
            {
                continue;
            }
        }

        return redirect('releases/show/' . $release_id);
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function show($id)
    {
        $release = Release::findOrFail($id);

        $uploaded_tracks = $release->tracks;

        return response()->json($uploaded_tracks);

    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function edit($id)
    {
        $release = Release::findOrFail($id);

        return view('tracks.edit')->with(['release' => $release]);
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function update(Request $request, $id)
    {
        //
    }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function destroy($id)
    {
        //
    }
}
