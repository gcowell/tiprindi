@extends('app')

@section('data_title') listener-create @stop

@section('content')


@if ($errors->any())
<ul class="alert alert-danger">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif
<div id="stripe_errors"></div>

{!! Form::open(['url' => 'listeners/create', 'id' => 'listener-form'])!!}

<div class="form-group" id="currency_group">
    {!! Form::label('currency', 'Currency ') !!}
    {!! Form::select('currency', ['USD' => 'USD', 'GBP' => 'GBP', 'EUR' => 'EUR'], 'GBP', ['class' => 'form-control', 'id' => 'currency', 'autocomplete' => 'off']) !!}
</div>

<div class="form-group" id="like_value_group">
    {!! Form::label('like_value', 'How Much For a Like: ') !!}
    <div class="input-symbol">
        <span>&pound;</span>
        {!! Form::input('number', 'like_value', null, ['class' => 'form-control', 'id' => 'like_value', 'autocomplete' => 'off']) !!}
    </div>
</div>

<div class="form-group" id="love_value_group">
    {!! Form::label('love_value', 'How Much For a Love: ') !!}
    <div class="input-symbol">
        <span>&pound;</span>
        {!! Form::input('number', 'love_value', null, ['class' => 'form-control', 'id' => 'love_value', 'autocomplete' => 'off']) !!}
    </div>
</div>

<div class="form-group" id="stripe_input">
</div>

{!! Form::close() !!}

<div class="form-group" id="submit-group">
    <button id="submit-button">Submit</button>
</div>


@stop