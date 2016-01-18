@include('includes.car.prop', ['data' => ['carNumber[id]', $car->id]])

@include('includes.form.text', ['data' => ['carNumber[mark]', $car->mark, 'Марка']])
@include('includes.form.text', ['data' => ['carNumber[model]', $car->model, 'Модель']])
@include('includes.form.text', ['data' => ['carNumber[year]', $car->year, 'Год']])
@include('includes.form.text', ['data' => ['carNumber[number]', $car->number, 'Номер']])
@include('includes.form.text', ['data' => ['carNumber[color]', $car->color, 'Цвет']])
@include('includes.form.text', ['data' => ['carNumber[body_type]', $car->body_type, 'Тип кузова']])
