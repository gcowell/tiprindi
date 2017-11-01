@extends('app')

@section('content')

<h2>{{ $artist->artist_name }}'s Profile</h2>
<br>
{{ $artist->bank_info }}
<br>
{{ $artist->currency }}


<br>
<br>
<a href="{{ url('/releases/' . $artist->id ) }}">Show Releases</a>
<br>
<a href="{{ url('/releases/create/' . $artist->id ) }}">Create a Release</a>

@stop