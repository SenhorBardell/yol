{{Form::open(['action' => $action, 'method' => 'GET'])}}
@foreach($groups as $group)
<div class="col-xs-3">
	<div class="form-group">
		<select class="form-control" name="{{$group['param']}}">
			<option
					value=""
					disabled
					@unless ($group['active'])
                        selected
                    @endunless
					>{{$group['name']}}</option>
			@foreach($group['data'] as $option)
				<option value="{{$option['id']}}"
				@if ($group['active'] == $option['id']) selected @endif
						>{{$option['name']}}</option>
			@endforeach
		</select>
	</div>
</div>
@endforeach

<div class="col-xs-3">
	{{Form::submit('Filter', ['class' => 'btn btn-primary btn-md'])}}
</div>

{{Form::close()}}