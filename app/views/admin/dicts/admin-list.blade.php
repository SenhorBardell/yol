@extends('layouts.admin')

@section('content')

    @include('includes.admin.mainHeader')

    @include('includes.admin.leftcolumn')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="row">
                <div class="col-xs-4">
                    <h1>{{ $title or 'Тип авто'}}</h1>
                </div>

                <div class="col-xs-12 text-left">
                    @include('includes.actions', ['actions' => $actions])
                </div>
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

    </div><!-- /.content-wrapper -->
@stop
