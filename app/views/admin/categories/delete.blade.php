@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        <section class="content">

            <h4>Подтвердите удаление категории {{$category->title}}</h4>

            {{Form::open([
                'action' => ['CategoriesController@destroy', $category->id],
                'method' => 'DELETE'
            ])}}

            @include('includes.form.submit', ['name' => 'Подтвердить'])

            {{Form::close()}}

        </section>

    </div>
@stop