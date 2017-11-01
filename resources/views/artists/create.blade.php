@extends('app')

@section('data_title') artist-create @stop

@section('content')

@if ($errors->any())
<ul class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif
<div id="stripe_errors"></div>

{!! Form::open(['url' => 'artists/create', 'id' => 'artist-form', 'files' => 'true'])!!}

<div class="form-group" id="artist_name_group">
    {!! Form::label('artist_name', 'Artist Name: ') !!}
    {!! Form::input('text', 'artist_name', null, ['class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="currency_group">
    {!! Form::label('currency', 'Currency ') !!}
    {!! Form::select('currency', ['USD' => 'USD', 'GBP' => 'GBP', 'EUR' => 'EUR'], 'GBP', ['id' => 'currency', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="name_group">
    {!! Form::label('name', 'Your Name: ') !!}
    {!! Form::input('text', 'first_name', null, ['id' => 'first_name', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
    {!! Form::input('text', 'last_name', null, ['id' => 'last_name', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="dob_group">
    {!! Form::label('dob', 'Date of Birth: ') !!}
    {!! Form::input('date', 'dob', null, ['id' => 'dob','class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>


<div class="form-group" id="line1_group">
    {!! Form::label('line1', 'Address: ') !!}
    {!! Form::input('text', 'line1', null, ['id' => 'line1', 'class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="city_group">
    {!! Form::label('city', 'City: ') !!}
    {!! Form::input('text', 'city', null, ['id' => 'city','class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="postal_code_group">
    {!! Form::label('postal_code', 'Postal Code: ') !!}
    {!! Form::input('text', 'postal_code', null, ['id' => 'postal_code','class' => 'form-control', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="legal_document_upload_group">
    {!! Form::label('legal_document', 'Please Upload ID: ') !!}
    {!! Form::file('legal_document', ['id' => 'legal_document', 'class' => 'image']) !!}
</div>

{!! Form::close() !!}

<div class="form-group" id="submit-group">
    <p>By clicking you agree to the terms and conditions</p>
    <button id="submit-button">Submit</button>
</div>


@stop