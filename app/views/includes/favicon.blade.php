@foreach($sizes as $size)
	<link
		rel="icon"
		type="image/png"
		href='<?php echo asset("img/favicon/favicon-{$size}x{$size}.png") ?>'
		sizes="32x32">
@endforeach
