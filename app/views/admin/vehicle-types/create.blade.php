@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        {{Form::open(['action' => 'VehicleTypeRefsController@store'])}}

        <section class="content">

            <h4>{{ $title or 'Редактирование марки' }}</h4>

            <div class="form-group">
                {{Form::label('name', 'Название модели RU')}}
                {{Form::text('ru', null,  ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
                {{Form::label('name', 'Название модели AZ')}}
                {{Form::text('az', null,  ['class' => 'form-control'])}}
            </div>

            @include('includes.form.submit', ['name' => 'Создать'])

        </section>

        {{Form::close()}}

    </div>
@stop