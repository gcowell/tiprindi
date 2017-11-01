@extends('app')

@section('content')

@if($user->isUnassociated())

    <a href="{{ url('/listeners/create')}}">Create a Listener Profile</a>
    <br>
    <a href="{{ url('/artists/create')}}">Create an Artist Profile</a>

@else



    <h1>{{$user->name}}'s Dashboard</h1>

    @if($user->isArtist())

        <h2>My Artists</h2>

        @foreach ($user->artist as $artist)

        <a href="{{ url('/artists/show/' . $artist->id) }}">{{ $artist->artist_name }}</a>
        <hr>

        @endforeach


        <a href="{{url('/artists/create')}}">Add an artist</a>
        <br>

    @else

        <a href="{{ url('/artists/create')}}">Create an Artist Profile</a>
        <br>


    @endif



    @if ($user->isListener())


        <br>


    @else

        <a href="{{ url('/listeners/create')}}">Create a Listener Profile</a>
        <br>


    @endif




@endif



@stop