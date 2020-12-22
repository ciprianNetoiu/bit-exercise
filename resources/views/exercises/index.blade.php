<?php
/**
 * @var array $listOfColors
 */
?>

@extends('layouts/main')

@section ('content')

    <div class="row">
        <div class="col-md-6">
            {!! Form::open(['action' => 'App\Http\Controllers\ExercisesController@create', 'method'=>'post', 'enctype' => 'multipart/form-data', 'id'=>'exercise-form']) !!}
            @csrf
            <div class="form-group">
                {{Form::label('color_number', 'number of colors')}}
                {{Form::number('color_number', '', ['class' => 'form-control', 'placeholder' => 'Number', 'id'=>'color-number'])}}
            </div>
            <div id="color-list-box"></div>
            {{Form::submit('Submit', ['class'=>'btn btn-primary hide', 'id'=>'submit-exercise'])}}
            <div id="error-box"></div>
            {!! Form::close() !!}
        </div>
        <div id="answer-box" class="col-md-6">

        </div>
    </div>


@endsection
<script>
    window.listOfCollors={!!$listOfColors!!};
</script>
