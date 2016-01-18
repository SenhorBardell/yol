@include('includes.form.text', ['data' => ['geo[long]', $geo->long, 'Долгота']])

@include('includes.form.text', ['data' => ['geo[lat]', $geo->lat, 'Широта']])

@include('includes.form.text', ['data' => ['geo[location]', $geo->location, 'Место']])