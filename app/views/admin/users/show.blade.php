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
                <div class="col-xs-12 text-left">
                    @include('includes.admin.user-actions', ['actions' => $actions])
                </div>
            </div>

            <section class="content">
                <div class="row">

                    <div class="col-xs-2">
                        <div class="avatar-user-block">
                            <div class="avatar-image-block">
                                {{HTML::image($img)}}
                            </div>
                            <div class="col-md-12">
                                ID: {{$id}}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        @include('includes.admin.user-labels', ['data' => $user])
                    </div>

                    <div class="form-group col-xs-10">
                        <label for="dtp_input2" class="col-md-4 control-label formedit-label">Показатели</label>
                        @include('includes.admin.user-statistics', ['data' => $statistics])
                    </div>
                </div>

            </section>

        </section>

    </div>
@stop