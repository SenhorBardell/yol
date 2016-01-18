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
                <div class="col-xs-8 text-right">
                    @include('includes.actions', ['actions' => [
                        #['link' => '#modal1', 'text' => 'Удалить выбранное'],
                        ['link' => "/admin/models/create", 'text' => 'Добавить'],
                    ]])
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
