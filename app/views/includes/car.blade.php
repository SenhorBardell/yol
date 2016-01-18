@include('includes.car.prop', ['data' => ['car[id]', $car->id]])

@include('includes.form.text', ['data' => ['car[mark]', $car->mark, 'Марка']])
@include('includes.form.text', ['data' => ['car[model]', $car->model, 'Модель']])
@include('includes.form.text', ['data' => ['car[year]', $car->year, 'Год']])
@include('includes.form.text', ['data' => ['car[color]', $car->color, 'Цвет']])
@include('includes.form.text', ['data' => ['car[body_type]', $car->body_type, 'Тип кузова']])