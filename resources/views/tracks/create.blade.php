@extends('app')

@section('data_title')track-uploader @stop

@section('content')

<div class="hidden">
    {!! Form::token() !!}
</div>

<div id="trackList" class="list-group">

    <div id="track-group-1" class="row track-row list-group-item">

        <div class="col-md-2">

            <div class="form-group track-number-group">
                {!! Form::label('track_number', 'Track Number: ') !!}
                {!! Form::input('number', 'track_number', '1', ['class' => 'form-control track-number', 'autocomplete' => 'off', 'readonly' => true]) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group track-title-group">
                {!! Form::label('track_title', 'Track Title: ') !!}
                {!! Form::input('text', 'track_title', null, ['class' => 'form-control track-title', 'autocomplete' => 'off']) !!}
            </div>
        </div>
        <div class="hidden">

            <div class="form-group upload-id-group">
                {!! Form::input('text', 'upload_id', 'null', ['class' => 'form-control upload-id', 'autocomplete' => 'off', 'readonly' => true]) !!}
            </div>
        </div>

        <div class="col-md-3">
            <form action="{{'/tracks/create/' . $release->id}}" class="dropzone" id="track-dropzone-1"></form>
        </div>

        <div class="col-md-1 delete-button-div">
        </div>

    </div>

</div>


@if ($errors->any())
<ul class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif


<div class="row">
    <a id="add-a-row">Add another track</a>
    <br>
</div>

<button type="button" class="btn btn-primary" id="finish">Finished</button>



@stop