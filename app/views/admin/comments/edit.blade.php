@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        <section class="content">

            {{Form::model($comment, [
                'action' => ['CommentsController@adminUpdate', $comment->id],
                'method' => 'PATCH'
            ])}}

            @include('includes.postable', ['postable' => $comment])


            <div class="row">
                <div class="col-lg-12">
                    @include('includes.form.submit', ['name' => 'Изменить'])
                </div>
            </div>
        </section>

        {{Form::close()}}
    </div>
@stop