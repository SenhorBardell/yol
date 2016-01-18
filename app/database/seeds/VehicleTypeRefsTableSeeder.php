<?php

class VehicleTypeRefsTableSeeder extends Seeder {

	public function run() {

		VehicleTypeRef::truncate();
		BodyTypeRef::truncate();
		DB::table('body_type_ref_model_ref')->truncate();

		$vehicleTypes = [
			[
				'az' => 'Truck',
				'ru' => 'Грузовик',
				'bodyTypes' => [
					['ru' => 'Фургон', 'az' => 'Van'],
					['ru' => 'Бортовой грузовик', 'az' => 'Flatbed truck'],
					['ru' => 'Самосвал', 'az' => 'Tipper'],
					['ru' => 'Тягач', 'az' => 'Mule'],
					['ru' => 'Эвакуатор', 'az' => 'Tow truck'],
					['ru' => 'Изотерм', 'az' => 'Isothermal van'],
					['ru' => 'Битумовоз', 'az' => 'Bitumen'],
					['ru' => 'Бензовоз', 'az' => 'Tanker'],
					['ru' => 'Газовоз', 'az' => 'Gas Truck'],
					['ru' => 'Водовоз', 'az' => 'Water truck'],
					['ru' => 'Рыбовоз', 'az' => 'Fish truck'],
					['ru' => 'Химцистерна', 'az' => 'Chemical truck'],
					['ru' => 'Пищевая цистерна', 'az' => 'Food truck'],
					['ru' => 'Автовоз', 'az' => 'Autotransporter'],
					['ru' => 'Контейнеровоз', 'az' => 'Container truck'],
					['ru' => 'КУНГ', 'az' => 'Military truck'],
					['ru' => 'Лесовоз', 'az' => 'Timber truck'],
					['ru' => 'Рефрижератор', 'az' => 'Refrigerator truck'],
					['ru' => 'Цементовоз', 'az' => 'Cement truck'],
					['ru' => 'Зерновоз', 'az' => 'Grain truck']
				]
			], [
				'az' => 'Car',
				'ru' => 'Легковая',
				'bodyTypes' => [
					[ 'ru' => 'Седан', 'az' => 'Sedan'],
					[ 'ru' => 'Универсал', 'az' => 'Universal'],
					[ 'ru' => 'Хетчбэк', 'az' => 'Hatchback'],
					[ 'ru' => 'Лифтбэк', 'az' => 'Liftback'],
					[ 'ru' => 'Купе', 'az' => 'Coupe'],
					[ 'ru' => 'Кабрио', 'az' => 'Cabrio'],
					[ 'ru' => 'Родстер', 'az' => 'Rodster'],
					[ 'ru' => 'Тарга', 'az' => 'Targa'],
					[ 'ru' => 'Лимузин', 'az' => 'Limousine'],
					[ 'ru' => 'Минивен', 'az' => 'Minivan'],
					[ 'ru' => 'Компактвен', 'az' => 'Compactvan'],
					[ 'ru' => 'Микровен', 'az' => 'Microvan'],
					[ 'ru' => 'Внедорожник', 'az' => 'SUV'],
					[ 'ru' => 'Кроссовер', 'az' => 'Crossover'],
					[ 'ru' => 'Пикап', 'az' => 'Pickup'],
					[ 'ru' => 'Caddy-фургон', 'az' => 'Caddyvan'],
					[ 'ru' => 'Таун-кар', 'az' => 'Towncar']
				]
			], [
				'az' => 'Bike',
				'ru' => 'Мототехника',
				'bodyTypes' => [
					[ 'ru' => 'Мотоцикл', 'az' => 'Motorcycle'],
					[ 'ru' => 'Скутер', 'az' => 'Scooter'],
					[ 'ru' => 'Мопед', 'az' => 'Moped'],
					[ 'ru' => 'Квадроцикл', 'az' => 'ATV'],
					[ 'ru' => 'Трицикл', 'az' => 'Tricycle'],
					[ 'ru' => 'Багги', 'az' => 'Buggy']
				]
			], [
				'az' => 'Bus',
				'ru' => 'Автобус',
				'bodyTypes' => [
					[ 'ru' => 'Микроавтобус', 'az' => 'Minibus'],
					[ 'ru' => 'Городской автобус', 'az' => 'City bus'],
					[ 'ru' => 'Междугородний автобус', 'az' => 'Intercity bus'],
					[ 'ru' => 'Пригородный автобус', 'az' => 'Suburban bus'],
					[ 'ru' => 'Экскурсионный автобус', 'az' => 'Tour bus'],
					[ 'ru' => 'Школьный автобус', 'az' => 'School bus']
				]
			]
		];

		foreach ($vehicleTypes as $vehicleType) {
			VehicleTypeRef::create(['ru' => $vehicleType['ru'], 'az' => $vehicleType['az']]);

			// Vehicle types and Body type must be separate
			array_map(function($bodyType) {
				BodyTypeRef::create(['ru' => $bodyType['ru'], 'az' => $bodyType['az']]);
			}, $vehicleType['bodyTypes']);

		}

	}

}