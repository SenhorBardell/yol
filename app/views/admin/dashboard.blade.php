@extends('layouts.admin')

@section('content')

@include('includes.admin.mainHeader')

@include('includes.admin.leftcolumn')

<div class="content-wrapper">

	<section class="content-header">
		<div class="row">
			<div class="col-xs-4">
				<h1>{{ $title or 'Пользователи'}}</h1>
			</div>
			<div class="col-xs-8 text-right">
			@include('includes.actions', ['actions' => [
				['link' => '#modal1', 'text' => 'Разослать'],
				['link' => '#modal2', 'text' => 'Опубликовать в ленту'],
				['link' => '#modal3', 'text' => 'Удалить выбранное'],
				['link' => '#modal4', 'text' => 'Добавить']
			]])
<!--				<button class="btn btn-primary btn-md margin">Удалить выбранное</button>-->
			</div>
		</div>

		<div class="row">
			@include('includes.filter')
		</div>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			@include('includes.table', [
				'columns' => [
					'Checkbox', 'Блок', 'Имя', 'Почта',
					'Телефон', 'Город', 'Возраст', 'Сообщений',
					'', '', ''
				], 'values' => [
					['', '', 'Вася', 'babkin@mail.ru', '83453465655',
					'Крыжопольск', 14, 1, 'Разб', 'Удал', 'Ред'],
					['', '', 'Вася', 'babkin@mail.ru', '83453465655',
					'Крыжопольск', 14, 1, 'Разб', 'Удал', 'Ред']
				]])
		</div>
	</section>

</div><!-- /.content-wrapper -->
@stop