@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        {{Form::model($bodyType, [
            'action' => ['VehicleTypeRefsController@update', $bodyType->id],
            'method' => 'PATCH'
        ])}}

        <section class="content">

            <h4>{{ $title or 'Редактирование' }}</h4>

            <div class="form-group">
                {{Form::label('name', 'Название RU')}}
                {{Form::text('ru', $bodyType->ru,  ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
                {{Form::label('name', 'Название AZ')}}
                {{Form::text('az', $bodyType->az,  ['class' => 'form-control'])}}
            </div>

            <div class="modal-footer">
                {{Form::submit('Изменить', ['class' => 'btn btn-primary'])}}
            </div>

        </section>

        {{Form::close()}}

    </div>
@stop