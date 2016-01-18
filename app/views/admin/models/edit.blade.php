@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        {{Form::model($model, [
            'action' => ['ModelRefsController@update', $model->marks->id, $model->id, ],
            'method' => 'PATCH'
        ])}}

        <section class="content">

            <h4>{{ $title or 'Редактирование марки' }}</h4>

            <div class="form-group">
                {{Form::label('name', 'Название модели')}}
                {{Form::text('name', $model->name,  ['class' => 'form-control'])}}
            </div>

            <h5>Тип кузова</h5>
            <div class="form-group">
                @foreach($vehicle_types as $vehicle)
                    <div class="checkbox icheck">
                        {{Form::checkbox("vehicle_type[]", $vehicle['id'], $vehicle['exists'])}}
                        {{link_to("admin/vehicle-types/{$vehicle['id']}", $vehicle['ru'])}}
                    </div>
                @endforeach
            </div>

            <h5>Макрка, к которой принадлежит модель</h5>
            <div class="form-group">
                @foreach($marks as $mark)
                    <div class="checkbox icheck">
                        {{Form::radio('mark', $mark['id'])}}
                        {{link_to("admin/marks/{$mark['id']}", $mark['name'])}}
                    </div>
                @endforeach
            </div>

            <div class="modal-footer">
                {{Form::submit('Изменить', ['class' => 'btn btn-primary'])}}
            </div>

        </section>

        {{Form::close()}}

    </div>
@stop
