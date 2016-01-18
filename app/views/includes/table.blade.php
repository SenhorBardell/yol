<div class="col-xs-12">
	<div class="box">
		<div class="box-body">
			<table id="example2" class="table table-bordered table-hover">
				<thead>
				<tr>
					<th></th>
					@foreach($columns as $column)
						<th>{{$column}}</th>
					@endforeach
				</tr>
				</thead>
				<tbody>
					@foreach($values as $value)
						<tr>
							<td><div class="checkbox icheck"><input type="checkbox"></div></td>
							@foreach($value as $data)
								<td>{{$data}}</td>
							@endforeach
							{{--<td><a  href="#modal-container-3" data-toggle="modal">Редактировать</a></td>--}}
						</tr>
					@endforeach
				</tbody>
			</table>

			<div class="row">
				<div class="col-xs-12">
					<div class="">
						{{$links}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

