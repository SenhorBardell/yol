@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        {{Form::open(['action' => 'ModelRefsController@store'])}}

        <section class="content">

            <h4>{{ $title or 'Редактирование марки' }}</h4>

            <div class="form-group">
                {{Form::label('name', 'Название марки')}}
                {{Form::text('name', null,  ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
                @foreach($vehicles as $vehicle)
                    <div class="checkbox icheck">
                        {{Form::radio("vehicle_type", $vehicle['id'], $vehicle['exists'])}}
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

            @include('includes.form.submit', ['name' => 'Создать'])

        </section>

        {{Form::close()}}

    </div>
@stop