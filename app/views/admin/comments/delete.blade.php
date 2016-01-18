@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        <section class="content">

            <h4>Подтвердите удаление комментария {{$comment->id}}</h4>

            {{Form::open([
                'action' => ['CommentsController@adminDestroy', $comment->id],
                'method' => 'DELETE'
            ])}}

            @include('includes.form.submit', ['name' => 'Подтвердить'])

            {{Form::close()}}

        </section>

    </div>
@stop