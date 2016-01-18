@include('includes.car.prop', ['data' => ['id', $car->id]])

@include('includes.form.text', ['data' => ['mark', $car->mark, 'Марка']])
@include('includes.form.text', ['data' => ['model', $car->model, 'Модель']])
@include('includes.form.text', ['data' => ['year', $car->year, 'Год']])
