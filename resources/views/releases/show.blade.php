@extends('app')

@section('content')

<h2>{{ $release->release_title }}</h2>
<p>Release Date: {{ $release->release_date->format('d m Y') }}</p>
<p>Release Type: {{ $release->release_type }}</p>

@foreach($release->tracks as $track)

<p>{{ $track->track_number}} - {{ $track->track_title}} </p>
<p>Filename: {{ $track->original_filename }}</p>
<p>Status: @if($track->conversion_complete == 0) Processing @else Live @endif</p>

@endforeach
<br>
<a href="{{ url('/tracks/edit/' . $release->id ) }}">Edit Tracks</a>
<br>
<a href="{{ url('/releases/edit/' . $release->id ) }}">Edit Release Details</a>
<br>



<br>
<br>
<a href="{{ url('/releases/' . $release->artist->id ) }}">Back to {{$release->artist->artist_name }}'s Releases </a>




@stop