@extends('app')

@section('content')

<h2>{{ $artist->artist_name }}'s Releases</h2>

@foreach ($releases as $release)

<h3>{{ $release->release_title }}</h3>
<br>
<a href="{{ url('/releases/show/' . $release->id ) }}">View Release</a>
<hr>

@endforeach
<br>
<a href="{{ url('/releases/create/' . $artist->id ) }}">Create a Release</a>

<br>
<a href="{{ url('/artists/show/' . $artist->id ) }}">Back to {{ $artist->artist_name }}</a>



@stop