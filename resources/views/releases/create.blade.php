@extends('app')

@section('content')

{!! Form::open(['url' => 'releases/create/' . $artist->id, 'id' => 'release-form'])!!}

@if ($errors->any())
<ul class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif

<div class="form-group" id="release_title">
    {!! Form::label('release_title', 'Release Title: ') !!}
    {!! Form::input('text', 'release_title', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="release_date">
    {!! Form::label('release_date', 'Release Date: ') !!}
    {!! Form::input('date', 'release_date', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="release_type">
    {!! Form::label('release_type', 'Release Type: ') !!}
    {!! Form::input('text', 'release_type', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

{!! Form::submit('Submit') !!}

{!! Form::close() !!}


@stop