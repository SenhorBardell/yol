@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        {{Form::open([
            'action' => ['CategoriesController@store'],
        ])}}

        <section class="content">

            <h4>{{ $title or 'Редактирование' }}</h4>

            @foreach($data as $row)
                @include('includes.form.text', ['data' => $row])
            @endforeach

            @include('includes.form.submit', ['name' => 'Создать'])
        </section>

        {{Form::close()}}

    </div>
@stop