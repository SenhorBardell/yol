@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-xs-4">
                    <h1>{{ $title or 'Индекс'}}</h1>
                </div>
                <div class="col-xs-8 text-right">
                    @foreach($actions as $action)
                        {{$action}}
                    @endforeach
                </div>
            </div>

            <div class="row">
                @include('includes.filters', ['groups' => $filters, 'action' => 'AdminUsersController@index'])
            </div>
        </section>

        <section class="content">
            <div class="row">
                @include('includes.table', [
                    'columns' => $columns,
                    'values' => $data,
                    'links' => $links
                ])
            </div>

        </section>

    </div>
@stop
