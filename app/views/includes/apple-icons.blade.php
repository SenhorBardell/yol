@foreach($sizes as $size)
	<link
		rel="apple-touch-icon"
		sizes="{{ $size }}x{{ $size }}"
		href='<?php echo asset("img/apple-touch-icon-{$size}x{$size}.png") ?>'>
@endforeach
