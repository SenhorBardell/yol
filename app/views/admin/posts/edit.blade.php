@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        <section class="content">

            {{Form::model($post, [
                'action' => ['PostsController@adminUpdate', $post->id],
                'method' => 'PATCH'
            ])}}

            @include('includes.postable', ['postable' => $post])

            <div class="form-group">
                @include('includes.form.submit', ['name' => 'Изменить'])
            </div>

        </section>

        {{Form::close()}}
    </div>
@stop