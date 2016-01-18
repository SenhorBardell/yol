@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        {{Form::model($vehicleType, [
            'action' => ['VehicleTypeRefsController@update', $vehicleType->id],
            'method' => 'PATCH'
        ])}}

        <section class="content">

            <h4>{{ $title or 'Редактирование марки' }}</h4>

            <div class="form-group">
                {{Form::label('name', 'Название модели RU')}}
                {{Form::text('ru', $vehicleType->ru,  ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
                {{Form::label('name', 'Название модели AZ')}}
                {{Form::text('az', $vehicleType->az,  ['class' => 'form-control'])}}
            </div>

            <div class="modal-footer">
                {{Form::submit('Изменить', ['class' => 'btn btn-primary'])}}
            </div>

        </section>

        {{Form::close()}}

    </div>
@stop