@extends('layouts.admin')

@section('content')

	@include('includes.admin.mainHeader')

	@include('includes.admin.leftcolumn')

	<div class="content-wrapper">

		{{Form::model($mark, [
			'action' => ['MarkRefsController@update', $mark->id],
			'method' => 'PATCH'
		])}}

		<section class="content">

			<h4>{{ $title or 'Редактирование марки' }}</h4>

			<div class="form-group">
				{{Form::label('name', 'Название марки')}}
				{{Form::text('name', $mark->name,  ['class' => 'form-control'])}}
			</div>

			<div class="form-group">
				@foreach($vehicles as $vehicle)
					<div class="checkbox icheck">
                        {{Form::checkbox("vehicle_type[]", $vehicle['id'], $vehicle['exists'])}}
						{{link_to("admin/vehicle-types/{$vehicle['id']}", $vehicle['ru'])}}
					</div>
				@endforeach
			</div>

			<div class="modal-footer">
				{{Form::submit('Изменить', ['class' => 'btn btn-primary'])}}
			</div>

		</section>

		{{Form::close()}}

	</div>
@stop
