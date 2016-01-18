<?php
class CategoriesCarsTableSeeder extends Seeder {

	public function run() {
		DB::statement('DELETE FROM categories WHERE id NOT BETWEEN 1 AND 58');
		DB::statement("SELECT setval('categories_id_seq', 58);");

		$categories = [
			[
				'name'       => 'ABM',
				'categories' => [
					'Мототехника ABM'
				]
			],
			[
				'name'       => 'AC Cars',
				'categories' => [
					'AC Cars Ace',
					'AC Cars Aceca',
					'AC Cars Cobra'
				]
			],
			[
				'name'       => 'Access',
				'categories' => [
					'Мототехника Access'
				]
			],
			['name'       => 'Acura',
				'categories' => [
					'Acura CL',
					'Acura CSX',
					'Acura EL',
					'Acura ILX',
					'Acura Integra',
					'Acura MDX',
					'Acura NSX',
					'Acura RDX',
					'Acura RL',
					'Acura RLX',
					'Acura RSX',
					'Acura TL',
					'Acura TLX',
					'Acura TSX',
					'Acura ZDX']
			],

			[
				'name'       => 'Adly',
				'categories' => ['Мототехника Adly']
			],

			['name'       => 'Aeon',
				'categories' => ['Мототехника Aeon']
			],

			['name'       => 'AIE Motor',
				'categories' => ['Мототехника AIE Motor']
			],

			['name'       => 'Alfa Romeo',
				'categories' => ['Alfa Romeo 145',
					'Alfa Romeo 146',
					'Alfa Romeo 147',
					'Alfa Romeo 156',
					'Alfa Romeo 159',
					'Alfa Romeo 166',
					'Alfa Romeo 4C',
					'Alfa Romeo 8C',
					'Alfa Romeo Brera',
					'Alfa Romeo Giulietta',
					'Alfa Romeo GT',
					'Alfa Romeo GTV',
					'Alfa Romeo MiTo',
					'Alfa Romeo Spider']
			],

			['name'       => 'Amur',
				'categories' => ['Грузовики Amur']
			],

			['name'       => 'Apollo',
				'categories' => ['Мототехника Apollo']
			],

			['name'       => 'Aprilia',
				'categories' => ['Мототехника Aprilia']
			],

			['name'       => 'Arctic Cat',
				'categories' => ['Мототехника Arctic Cat']
			],

			['name'       => 'Arlen Ness',
				'categories' => ['Мототехника Arlen Ness']
			],

			['name'       => 'Armada',
				'categories' => ['Мототехника Armada']
			],

			['name'       => 'Arora',
				'categories' => ['Мототехника Arora']
			],

			['name'       => 'Asa',
				'categories' => ['Мототехника Asa']
			],

			['name'       => 'Asia',
				'categories' => ['Автобусы Asia']
			],

			['name'       => 'Asia Retona',
				'categories' => ['Asia Rocsta']
			],

			['name'       => 'Aston Martin',
				'categories' => ['Aston Martin Cygnet',
					'Aston Martin DB7',
					'Aston Martin DB9',
					'Aston Martin DBS',
					'Aston Martin ONE-77',
					'Aston Martin Rapide',
					'Aston Martin Vantage',
					'Aston Martin Vanquish',
					'Aston Martin Zagato',
					'Aston Martin Virage']
			],

			['name'       => 'Astra',
				'categories' => ['Грузовики Astra']
			],

			['name'       => 'Atlant',
				'categories' => ['Мототехника Atlant']
			],

			['name'       => 'Audi',
				'categories' => ['Audi 100',
					'Audi A1',
					'Audi A2',
					'Audi A3',
					'Audi A4',
					'Audi A4 Allroad',
					'Audi A5',
					'Audi A6',
					'Audi A6 Allroad',
					'Audi A7',
					'Audi A8',
					'Audi Q3',
					'Audi Q5',
					'Audi Q7',
					'Audi R8',
					'Audi RS Q3',
					'Audi RS3',
					'Audi RS4',
					'Audi RS5',
					'Audi RS6',
					'Audi RS7',
					'Audi S3',
					'Audi S4',
					'Audi S5',
					'Audi S6',
					'Audi S7',
					'Audi S8',
					'Audi SQ5',
					'Audi TT',
					'Audi TTS']
			],

			['name'       => 'Avantis',
				'categories' => ['Мототехника Avantis']
			],

			['name'       => 'Avia',
				'categories' => ['Грузовики Avia']
			],

			['name'       => 'AVM',
				'categories' => ['Мототехника AVM']
			],

			['name'       => 'Ayron',
				'categories' => ['Мототехника Ayron']
			],

			['name'       => 'AzSamand',
				'categories' => ['AzSamand Aziz',
					'AzSamand LX']
			],

			['name'       => 'BAIC',
				'categories' => ['BAIC A115',
					'BAIC A315',
					'BAIC A5',
					'BAIC K4',
					'BAIC R315',
					'BAIC X424',
					'BAIC XB4',
					'BAIC XB624',
					'BAIC XB628']
			],

			['name'       => 'Baltmotors',
				'categories' => ['Мототехника Baltmotors']
			],

			['name'       => 'Baotian Scooters',
				'categories' => ['Мототехника Baotian Scooters']
			],

			['name'       => 'Bars',
				'categories' => ['Мототехника Bars']
			],

			['name'       => 'Bashan',
				'categories' => ['Мототехника Bashan']
			],

			['name'       => 'BAW',
				'categories' => ['Грузовики BAW',
					'Автобусы BAW']
			],

			['name'       => 'BAZ',
				'categories' => ['Автобусы BAZ']
			],

			['name'       => 'Beifan',
				'categories' => ['Грузовики Beifan']
			],

			['name'       => 'BelAZ',
				'categories' => ['Грузовики BelAZ']
			],

			['name'       => 'Benelli',
				'categories' => ['Мототехника Benelli']
			],

			['name'       => 'Bentley',
				'categories' => ['Bentley Arnage',
					'Bentley Azure',
					'Bentley Brooklands',
					'Bentley Continental Flying Spur',
					'Bentley Continental GT',
					'Bentley Flying Spur',
					'Bentley Mulsanne']
			],

			['name'       => 'Beta',
				'categories' => ['Мототехника Beta']
			],

			['name'       => 'Big Bear Choppers',
				'categories' => ['Мототехника Big Bear Choppers']
			],

			['name'       => 'Big Dog Motorcycles',
				'categories' => ['Мототехника Big Dog Motorcycles']
			],

			['name'       => 'Bimota',
				'categories' => ['Мототехника Bimota']
			],

			['name'       => 'Bison',
				'categories' => ['Мототехника Bison']
			],

			['name'       => 'Blade',
				'categories' => ['Мототехника Blade']
			],

			['name'       => 'BM',
				'categories' => ['Мототехника BM']
			],

			['name'       => 'BMW',
				'categories' => ['Мототехника BMW',
					'BMW 1-series',
					'BMW 2-series',
					'BMW 3-series',
					'BMW 4-series',
					'BMW 5-series',
					'BMW 6-series',
					'BMW 7-series',
					'BMW i3',
					'BMW i8',
					'BMW M3',
					'BMW M4',
					'BMW M5',
					'BMW M6',
					'BMW X1',
					'BMW X3',
					'BMW X4',
					'BMW X5',
					'BMW X5 M',
					'BMW X6',
					'BMW X6 M',
					'BMW Z3',
					'BMW Z4',
					'BMW Z8',
					'BMW Alpina',
					'BMW Alpina B3',
					'BMW Alpina B4',
					'BMW Alpina B5',
					'BMW Alpina B6',
					'BMW Alpina B7',
					'BMW Alpina B10',
					'BMW Alpina B12',
					'BMW Alpina D3',
					'BMW Alpina D5',
					'BMW Alpina D10',
					'BMW Alpina RLE',
					'BMW Alpina Roadster S',
					'BMW Alpina Roadster V8',
					'BMW Alpina XD3']
			],

			['name'       => 'Boom Trikes',
				'categories' => ['Мототехника Boom Trikes']
			],

			['name'       => 'Boqdan',
				'categories' => ['Автобусы Boqdan']
			],

			['name'       => 'Boss Hoss',
				'categories' => ['Мототехника Boss Hoss']
			],

			['name'       => 'Bova',
				'categories' => ['Автобусы Bova']
			],

			['name'       => 'Brilliance',
				'categories' => ['Brilliance BS2',
					'Brilliance BS4',
					'Brilliance BS6',
					'Brilliance M1',
					'Brilliance M2',
					'Brilliance M3',
					'Brilliance H 230',
					'Brilliance H 320',
					'Brilliance H 530',
					'Brilliance V5']
			],
			['name'       => 'Bronto',
				'categories' => ['Bronto Rıs']
			],

			['name'       => 'BRP',
				'categories' => ['Мототехника BRP']
			],

			['name'       => 'BSE',
				'categories' => ['Мототехника BSE']
			],

			['name'       => 'Buell',
				'categories' => ['Мототехника Buell']
			],

			['name'       => 'Bugatti',
				'categories' => ['Bugatti EB 16.4 Veyron']
			],

			['name'       => 'Bugfaster',
				'categories' => ['Мототехника Bugfaster']
			],

			['name'       => 'Buggy Jump',
				'categories' => ['Мототехника Buggy Jump']
			],

			['name'       => 'Buick',
				'categories' => ['Buick Enclave',
					'Buick Excelle',
					'Buick GL8',
					'Buick LaCrosse',
					'Buick Le Sabre',
					'Buick Lucerne',
					'Buick Park Avenue',
					'Buick Rainier',
					'Buick Regal',
					'Buick Rendezvous',
					'Buick Riviera',
					'Buick Terraza']
			],

			['name'       => 'Bull',
				'categories' => ['Мототехника Bull']
			],

			['name'       => 'BYD',
				'categories' => ['BYD E6',
					'BYD F0',
					'BYD F3',
					'BYD F3R',
					'BYD F5',
					'BYD F6',
					'BYD F7',
					'BYD Flyer',
					'BYD G3',
					'BYD G6',
					'BYD i6',
					'BYD L3',
					'BYD M6',
					'BYD S6']
			],

			['name'       => 'Cadillac',
				'categories' => ['Cadillac ATS',
					'Cadillac BLS',
					'Cadillac Catera',
					'Cadillac CTS',
					'Cadillac De Ville',
					'Cadillac DTS',
					'Cadillac Eldorado',
					'Cadillac ELR',
					'Cadillac Escalade',
					'Cadillac Seville',
					'Cadillac SRX',
					'Cadillac STS',
					'Cadillac XLR',
					'Cadillac XTS']
			],

			['name'       => 'Cagiva',
				'categories' => ['Мототехника Cagiva']
			],

			['name'       => 'CAMC',
				'categories' => ['Грузовики CAMC']
			],

			['name'       => 'Campagna',
				'categories' => ['Мототехника Campagna']
			],

			['name'       => 'Can-Am',
				'categories' => ['Мототехника Can-Am']
			],

			['name'       => 'Cectek',
				'categories' => ['Мототехника Cectek']
			],

			['name'       => 'Celimo',
				'categories' => ['Мототехника Celimo']
			],

			['name'       => 'Centurion',
				'categories' => ['Мототехника Centurion']
			],

			['name'       => 'CFMoto',
				'categories' => ['Мототехника CFMoto']
			],

			['name'       => 'Changan (Chana)',
				'categories' => ['Грузовики Changan',
					'Changan Alsvin',
					'Changan Benni Mini',
					'Changan CM8',
					'Changan CS35',
					'Changan CX20',
					'Changan CX30',
					'Changan Eado',
					'Changan Honor',
					'Changan Love']
			],

			['name'       => 'ChangFeng',
				'categories' => ['ChangFeng Flying']
			],

			['name'       => 'Chery',
				'categories' => ['Chery A1 (Kimo)',
					'Chery A13 (Bonus)',
					'Chery A15 (Amulet)',
					'Chery A18 (Karry)',
					'Chery A21 (Fora)',
					'Chery A3 (M11)',
					'Chery A5',
					'Chery B11 (Oriental Son)',
					'Chery B14 (CrossEastar)',
					'Chery Cowin',
					'Chery E5',
					'Chery Eastar',
					'Chery Fulwin2',
					'Chery IndiS (S18D)',
					'Chery QQ (Sweet)',
					'Chery QQ6',
					'Chery Tiggo',
					'Chery Very']
			],

			['name'       => 'Chevrolet',
				'categories' => ['Грузовики Chevrolet',
					'Chevrolet Alero',
					'Chevrolet Astra',
					'Chevrolet Astro',
					'Chevrolet Avalanche',
					'Chevrolet Aveo',
					'Chevrolet Beretta',
					'Chevrolet Blazer',
					'Chevrolet Blazer K5',
					'Chevrolet Camaro',
					'Chevrolet Caprice',
					'Chevrolet Captiva',
					'Chevrolet Cavalier',
					'Chevrolet Celta',
					'Chevrolet Classic',
					'Chevrolet Cobalt',
					'Chevrolet Colorado',
					'Chevrolet Corsa',
					'Chevrolet Corsica',
					'Chevrolet Corvette',
					'Chevrolet Cruze',
					'Chevrolet Epica',
					'Chevrolet Equinox',
					'Chevrolet Evanda',
					'Chevrolet Express',
					'Chevrolet HHR',
					'Chevrolet Impala',
					'Chevrolet Kalos',
					'Chevrolet Lacetti',
					'Chevrolet Lanos',
					'Chevrolet Lumina',
					'Chevrolet Lumina APV',
					'Chevrolet LUV D-MAX',
					'Chevrolet Malibu',
					'Chevrolet Metro',
					'Chevrolet Monte Carlo',
					'Chevrolet MW',
					'Chevrolet Niva',
					'Chevrolet Nubira',
					'Chevrolet Omega',
					'Chevrolet Orlando',
					'Chevrolet Prizm',
					'Chevrolet Rezzo',
					'Chevrolet S-10',
					'Chevrolet Sail',
					'Chevrolet Silverado',
					'Chevrolet Sonic',
					'Chevrolet Spark (Matiz)',
					'Chevrolet SSR',
					'Chevrolet Suburban',
					'Chevrolet Tacuma',
					'Chevrolet Tahoe',
					'Chevrolet Tavera',
					'Chevrolet Tracker',
					'Chevrolet TrailBlazer',
					'Chevrolet Trans Sport',
					'Chevrolet Traverse',
					'Chevrolet Uplander',
					'Chevrolet Vectra',
					'Chevrolet Venture',
					'Chevrolet Viva',
					'Chevrolet Volt',
					'Chevrolet Zafira']
			],

			['name'       => 'Chrysler',
				'categories' => ['Chrysler 200',
					'Chrysler 300C',
					'Chrysler 300M',
					'Chrysler Aspen',
					'Chrysler Cirrus',
					'Chrysler Concorde',
					'Chrysler Crossfire',
					'Chrysler Delta',
					'Chrysler Grand Voyager',
					'Chrysler Intrepid',
					'Chrysler Le Baron',
					'Chrysler LHS',
					'Chrysler Nassau',
					'Chrysler Neon',
					'Chrysler New Yorker',
					'Chrysler PT Cruiser',
					'Chrysler Pacifica',
					'Chrysler Saratoga',
					'Chrysler Sebring',
					'Chrysler Stratus',
					'Chrysler Town & Country',
					'Chrysler Vision',
					'Chrysler Voyager']
			],

			['name'       => 'Citroen',
				'categories' => ['Грузовики Citroen',
					'Citroen Berlingo',
					'Citroen BX',
					'Citroen C-Crosser',
					'Citroen C-Elysee',
					'Citroen C1',
					'Citroen C2',
					'Citroen C3',
					'Citroen C3 Picasso',
					'Citroen C4',
					'Citroen C4 Aircross',
					'Citroen C4 L',
					'Citroen C4 Picasso',
					'Citroen C5',
					'Citroen C6',
					'Citroen C8',
					'Citroen DS3',
					'Citroen DS4',
					'Citroen DS5',
					'Citroen Evasion',
					'Citroen Jumper',
					'Citroen Jumpy',
					'Citroen Nemo',
					'Citroen Saxo',
					'Citroen Xantia',
					'Citroen XM',
					'Citroen Xsara',
					'Citroen Xsara Picasso',
					'Citroen ZX']
			],

			['name'       => 'Cobra',
				'categories' => ['Мототехника Cobra']
			],

			['name'       => 'Confederate',
				'categories' => ['Мототехника Confederate']
			],

			['name'       => 'Corsa',
				'categories' => ['Мототехника Corsa']
			],

			['name'       => 'CPI',
				'categories' => ['Мототехника CPI']
			],

			['name'       => 'Cronus',
				'categories' => ['Мототехника Cronus']
			],

			['name'       => 'CRZ',
				'categories' => ['Мототехника CRZ']
			],

			['name'       => 'CZ',
				'categories' => ['Мототехника CZ']
			],

			['name'       => 'Dacia',
				'categories' => ['Dacia Dokker',
					'Dacia Duster',
					'Dacia Lodgy',
					'Dacia Logan',
					'Dacia Sandero']
			],

			['name'       => 'Dadi',
				'categories' => ['Dadi City Leading',
					'Dadi Shuttle']
			],

			['name'       => 'Daelim',
				'categories' => ['Мототехника Daelim']
			],

			['name'       => 'Daewoo',
				'categories' => ['Грузовики Daewoo',
					'Автобусы Daewoo',
					'Daewoo Cielo',
					'Daewoo Damas',
					'Daewoo Espero',
					'Daewoo Gentra',
					'Daewoo Kalos',
					'Daewoo Korando',
					'Daewoo Lacetti',
					'Daewoo Lanos (Sens)',
					'Daewoo Leganza',
					'Daewoo Magnus',
					'Daewoo Matiz',
					'Daewoo Musso',
					'Daewoo Nexia',
					'Daewoo Nubira',
					'Daewoo Prince',
					'Daewoo Racer',
					'Daewoo Rezzo',
					'Daewoo Super Salon',
					'Daewoo Tacuma',
					'Daewoo Tico',
					'Daewoo Winstorm']
			],

			['name'       => 'DAF',
				'categories' => ['Грузовики DAF',
					'Автобусы DAF']
			],

			['name'       => 'Daihatsu',
				'categories' => ['Daihatsu Applause',
					'Daihatsu Atrai',
					'Daihatsu Be-go',
					'Daihatsu Boon',
					'Daihatsu Boon Luminas',
					'Daihatsu Charade',
					'Daihatsu Coo',
					'Daihatsu Copen',
					'Daihatsu Cuore',
					'Daihatsu Delta Wagon',
					'Daihatsu Esse',
					'Daihatsu Feroza',
					'Daihatsu Gran Move',
					'Daihatsu Hijet',
					'Daihatsu Materia',
					'Daihatsu Mira',
					'Daihatsu Move',
					'Daihatsu Naked',
					'Daihatsu Pyzar',
					'Daihatsu Sirion',
					'Daihatsu Sonica',
					'Daihatsu Storia',
					'Daihatsu Tanto',
					'Daihatsu Terios',
					'Daihatsu YRV']
			],

			['name'       => 'Daimler',
				'categories' => ['Daimler X350']
			],

			['name'       => 'Dali',
				'categories' => ['Автобусы Dali']
			],

			['name'       => 'Dandy',
				'categories' => ['Мототехника Dandy']
			],

			['name'       => 'Datsun',
				'categories' => ['Datsun on-DO']
			],

			['name'       => 'Dayun',
				'categories' => ['Мототехника Dayun']
			],

			['name'       => 'Derbi',
				'categories' => ['Мототехника Derbi']
			],

			['name'       => 'Derways',
				'categories' => ['Derways Aurora',
					'Derways Cowboy',
					'Derways Plutus',
					'Derways Shuttle']
			],

			['name'       => 'Desert Raven',
				'categories' => ['Мототехника Desert Raven']
			],

			['name'       => 'Desna',
				'categories' => ['Мототехника Desna']
			],

			['name'       => 'Dirtmax',
				'categories' => ['Мототехника Dirtmax']
			],

			['name'       => 'DKW',
				'categories' => ['Мототехника DKW']
			],

			['name'       => 'Dnepr',
				'categories' => ['Мототехника Dnepr']
			],

			['name'       => 'Dodge',
				'categories' => ['Dodge Avenger',
					'Dodge Caliber',
					'Dodge Caravan',
					'Dodge Challenger',
					'Dodge Charger',
					'Dodge Dakota',
					'Dodge Dart',
					'Dodge Durango',
					'Dodge Grand Caravan',
					'Dodge Intrepid',
					'Dodge Journey',
					'Dodge Magnum',
					'Dodge Neon',
					'Dodge Nitro',
					'Dodge Ram',
					'Dodge Stratus',
					'Dodge Viper']
			],

			['name'       => 'DongFeng',
				'categories' => ['Грузовики DongFeng',
					'Автобусы DongFeng',
					'DongFeng H30 Cross',
					'DongFeng Rich']
			],

			['name'       => 'Ducati',
				'categories' => ['Мототехника Ducati']
			],

			['name'       => 'Elit',
				'categories' => ['Мототехника Elit']
			],

			['name'       => 'E-Moto',
				'categories' => ['Мототехника E-Moto']
			],

			['name'       => 'ERF',
				'categories' => ['Грузовики ERF']
			],

			['name'       => 'Eurotex',
				'categories' => ['Мототехника Eurotex']
			],

			['name'       => 'Excelsior-Henderson',
				'categories' => ['Мототехника Excelsior-Henderson']
			],

			['name'       => 'Falcon',
				'categories' => ['Мототехника Falcon']
			],

			['name'       => 'FAW',
				'categories' => ['Грузовики FAW',
					'FAW Besturn B50',
					'FAW Besturn B70',
					'FAW Besturn B90',
					'FAW Besturn X80',
					'FAW HongQi H7 (C131)',
					'FAW HongQi HQ3',
					'FAW Jiaxing',
					'FAW Jinn',
					'FAW Oley',
					'FAW Sirius S80',
					'FAW V2',
					'FAW V5',
					'FAW Vela',
					'FAW Vita',
					'FAW Vita C1',
					'FAW Xiali N3',
					'FAW Xiali N5',
					'FAW Xiali N7']
			],

			['name'       => 'Feishen',
				'categories' => ['Мототехника Feishen']
			],

			['name'       => 'Ferrari',
				'categories' => ['Ferrari 360',
					'Ferrari 456M',
					'Ferrari 458',
					'Ferrari 550',
					'Ferrari 575M',
					'Ferrari 599',
					'Ferrari 612 Scaglietti',
					'Ferrari California',
					'Ferrari F12berlinetta',
					'Ferrari F430',
					'Ferrari FF',
					'Ferrari LaFerrari']
			],

			['name'       => 'Fiat',
				'categories' => ['Грузовики Fiat',
					'Fiat 1100',
					'Fiat 500',
					'Fiat Albea',
					'Fiat Barchetta',
					'Fiat Brava',
					'Fiat Bravo',
					'Fiat Cinquecento (500L)',
					'Fiat Coupe',
					'Fiat Croma',
					'Fiat Doblo',
					'Fiat Fiorino',
					'Fiat Freemont',
					'Fiat Idea',
					'Fiat Linea',
					'Fiat Marea',
					'Fiat Multipla',
					'Fiat Palio',
					'Fiat Panda',
					'Fiat Panorama',
					'Fiat Punto',
					'Fiat Qubo',
					'Fiat Scudo',
					'Fiat Sedici',
					'Fiat Seicento',
					'Fiat Siena',
					'Fiat Stilo',
					'Fiat Tempra',
					'Fiat Tipo',
					'Fiat Ulysse',
					'Fiat Uno']
			],

			['name'       => 'Fighter',
				'categories' => ['Мототехника Fighter']
			],

			['name'       => 'Fine Custom Mechanics',
				'categories' => ['Мототехника Fine Custom Mechanics']
			],

			['name'       => 'Ford',
				'categories' => ['Грузовики Ford',
					'Автобусы Ford',
					'Ford Aerostar',
					'Ford B-Max',
					'Ford Bronco',
					'Ford C-Max',
					'Ford Contour',
					'Ford Cougar',
					'Ford Courier',
					'Ford Crown Victoria',
					'Ford Ecosport',
					'Ford Edge',
					'Ford Escape',
					'Ford Escort',
					'Ford Excursion',
					'Ford Expedition',
					'Ford Explorer',
					'Ford F-150',
					'Ford F-250',
					'Ford F-350',
					'Ford F-450 (Super Duty)',
					'Ford Festiva',
					'Ford Fiesta',
					'Ford Five Hundred',
					'Ford Flex',
					'Ford Focus',
					'Ford Freestyle',
					'Ford Fusion',
					'Ford Galaxy',
					'Ford Granada',
					'Ford Grand C-Max',
					'Ford Ka',
					'Ford Kuga',
					'Ford Laser',
					'Ford Maverick',
					'Ford Mercury',
					'Ford Mondeo',
					'Ford Mustang',
					'Ford Probe',
					'Ford Puma',
					'Ford Ranchero',
					'Ford Ranger',
					'Ford S-Max',
					'Ford Scorpio',
					'Ford Sierra',
					'Ford Sport Track',
					'Ford Streetka',
					'Ford Taunus',
					'Ford Taurus',
					'Ford Tempo',
					'Ford Thunderbird',
					'Ford Tourneo Connect',
					'Ford Tourneo Custom',
					'Ford Windstar']
			],

			['name'       => 'Forsage',
				'categories' => ['Мототехника Forsage']
			],

			['name'       => 'Fortune',
				'categories' => ['Мототехника Fortune']
			],

			['name'       => 'Foton',
				'categories' => ['Грузовики Foton']
			],

			['name'       => 'Foton Midi',
				'categories' => ['Foton SUP C1',
					'Foton SUP C2',
					'Foton SUP C3',
					'Foton SUP CX',
					'Foton Tunland',
					'Foton View M ']
			],

			['name'       => 'Freightliner',
				'categories' => ['Грузовики Freightliner']
			],

			['name'       => 'Fun Cruiser',
				'categories' => ['Мототехника Fun Cruiser']
			],

			['name'       => 'Gabro',
				'categories' => ['Мототехника Gabro']
			],

			['name'       => 'Gamax',
				'categories' => ['Мототехника Gamax']
			],

			['name'       => 'Gas Gas',
				'categories' => ['Мототехника Gas Gas',
					'GAZ',
					'Грузовики GAZ',
					'Автобусы  GAZ',
					'GAZ 13',
					'GAZ 14',
					'GAZ 21',
					'GAZ 22',
					'GAZ 24',
					'GAZ 2410',
					'GAZ 3102',
					'GAZ 31029',
					'GAZ 3105',
					'GAZ 3110',
					'GAZ 31105',
					'GAZ 3111',
					'GAZ M-20 Pobeda',
					'GAZ M-21',
					'GAZ M-22',
					'GAZ Next',
					'GAZ Siber',
					'GAZ Sobol',
					'GAZ SAZ',
					'Грузовики GAZ-SAZ']
			],

			['name'       => 'Geely',
				'categories' => ['Мототехника Geely',
					'Geely CK (Otaka)',
					'Geely Emgrand 7 (EC7)',
					'Geely Emgrand 7 RV (EC7 RV)',
					'Geely Emgrand 8 (EC8)',
					'Geely Emgrand X7 (EX7)',
					'Geely FC (Vision)',
					'Geely GC2',
					'Geely GC5',
					'Geely GC6',
					'Geely GC7',
					'Geely MK',
					'Geely MK Cross']
			],

			['name'       => 'Genata',
				'categories' => ['Мототехника Genata']
			],

			['name'       => 'Geon',
				'categories' => ['Мототехника Geon']
			],

			['name'       => 'Gerdakar',
				'categories' => ['Мототехника Gerdakar']
			],

			['name'       => 'Gibbs',
				'categories' => ['Мототехника Gibbs']
			],

			['name'       => 'Gilera',
				'categories' => ['Мототехника Gilera']
			],

			['name'       => 'Ginaf',
				'categories' => ['Грузовики Ginaf']
			],

			['name'       => 'GMC',
				'categories' => ['Грузовики GMC',
					'GMC Acadia',
					'GMC Canyon',
					'GMC Envoy',
					'GMC Jimmy',
					'GMC Sierra',
					'GMC Suburban',
					'GMC Terrain',
					'GMC Yukon']
			],

			['name'       => 'Godzilla',
				'categories' => ['Мототехника Godzilla']
			],

			['name'       => 'Golaz',
				'categories' => ['Автобусы Golaz']
			],

			['name'       => 'Golden Gragon',
				'categories' => ['Автобусы Golden Dragon']
			],

			['name'       => 'Gonow',
				'categories' => ['Gonow AOOSED G5',
					'Gonow AOOSED GX5',
					'Gonow Jetstar',
					'Gonow Starry',
					'Gonow Troy',
					'Gonow Way']
			],

			['name'       => 'GRAZ',
				'categories' => ['Грузовики GRAZ',
					'Great Wall',
					'Great Wall C20R',
					'Great Wall C30',
					'Great Wall C50',
					'Great Wall Coolbear',
					'Great Wall Deer',
					'Great Wall Florid',
					'Great Wall H3',
					'Great Wall H5',
					'Great Wall H6',
					'Great Wall M2',
					'Great Wall M4',
					'Great Wall Pegasus',
					'Great Wall Peri',
					'Great Wall Safe',
					'Great Wall Sailor',
					'Great Wall Socool',
					'Great Wall Sing',
					'Great Wall Voleex C10',
					'Great Wall Voleex C20R',
					'Great Wall Voleex C30',
					'Great Wall Voleex C50',
					'Great Wall Voleex V80',
					'Great Wall Wingle 5',
					'Great Wall X240']
			],

			['name'       => 'Gryphon',
				'categories' => ['Мототехника Gryphon']
			],

			['name'       => 'Guowei',
				'categories' => ['Мототехника Guowei']
			],

			['name'       => 'GX Moto',
				'categories' => ['Мототехника GX Moto']
			],

			['name'       => 'Hafei',
				'categories' => ['Hafei Brio',
					'Hafei Lobo',
					'Hafei Princip',
					'Hafei Saibao',
					'Hafei Saima',
					'Hafei Sigma',
					'Hafei Simbo']
			],

			['name'       => 'Haima',
				'categories' => ['Haima 2',
					'Haima 3',
					'Haima 7',
					'Haima Freema',
					'Haima Fstar',
					'Haima M3',
					'Haima S7']
			],

			['name'       => 'Hania',
				'categories' => ['Грузовики Hania']
			],

			['name'       => 'Haobon',
				'categories' => ['Мототехника Haobon']
			],

			['name'       => 'Haojue',
				'categories' => ['Мототехника Haojue']
			],

			['name'       => 'Harley-Davidson',
				'categories' => ['Мототехника harley-Davidson']
			],

			['name'       => 'Higer',
				'categories' => ['Автобусы Higer']
			],

			['name'       => 'Highland',
				'categories' => ['Мототехника Highland']
			],

			['name'       => 'Hino',
				'categories' => ['Грузовики Hino']
			],

			['name'       => 'Hisun',
				'categories' => ['Мототехника Hisun']
			],

			['name'       => 'Hoka',
				'categories' => ['Грузовики Hoka']
			],

			['name'       => 'Honda',
				'categories' => ['Грузовики Honda',
					'Мототехника Honda',
					'Honda Accord',
					'Honda Airwave',
					'Honda Ascot',
					'Honda Ascot Innova',
					'Honda Avancier',
					'Honda Capa',
					'Honda City',
					'Honda Civic',
					'Honda Civic Ferio',
					'Honda Concerto',
					'Honda CR-V',
					'Honda CR-X',
					'Honda CR-Z',
					'Honda Crossroad',
					'Honda Crosstour',
					'Honda Domani',
					'Honda Edix',
					'Honda Element',
					'Honda Elysion',
					'Honda Fit',
					'Honda Fit Aria',
					'Honda Fit Shuttle',
					'Honda FR-V',
					'Honda Freed',
					'Honda HR-V',
					'Honda Insight',
					'Honda Inspire',
					'Honda Integra',
					'Honda Integra SJ',
					'Honda Jazz',
					'Honda Legend',
					'Honda Life',
					'Honda Logo',
					'Honda MDX',
					'Honda Mobilio',
					'Honda Mobilio Spike',
					'Honda Odyssey',
					'Honda Orthia',
					'Honda Partner',
					'Honda Passport',
					'Honda Pilot (MR-V)',
					'Honda Prelude',
					'Honda Rafaga',
					'Honda Ridgeline',
					'Honda S-MX',
					'Honda S 2000',
					'Honda Saber',
					'Honda Shuttle',
					'Honda Stepwgn',
					'Honda Stream',
					'Honda Torneo',
					'Honda Vamos',
					'Honda Vigor',
					'Honda Z',
					'Honda Zest']
			],

			['name'       => 'Honling',
				'categories' => ['Мототехника Honling']
			],

			['name'       => 'Hors',
				'categories' => ['Мототехника Hors']
			],

			['name'       => 'Howo',
				'categories' => ['Грузовики Howo']
			],

			['name'       => 'Huatian',
				'categories' => ['Мототехника Huatian']
			],

			['name'       => 'Hummer',
				'categories' => ['Hummer H1',
					'Hummer H2',
					'Hummer H3']
			],

			['name'       => 'Husaberg',
				'categories' => ['Мототехника Husaberg']
			],

			['name'       => 'Husqvarna',
				'categories' => ['Мототехника Husqvarna']
			],

			['name'       => 'Hyosung',
				'categories' => ['Мототехника Hyosung']
			],

			['name'       => 'Hyundai',
				'categories' => ['Грузовики Hyundai',
					'Автобусы Hyundai',
					'Hyundai Accent',
					'Hyundai Atos (Amica)',
					'Hyundai Avante',
					'Hyundai Centennial',
					'Hyundai Coupe',
					'Hyundai Elantra',
					'Hyundai Equus',
					'Hyundai Excel',
					'Hyundai Galloper',
					'Hyundai Genesis',
					'Hyundai Getz',
					'Hyundai Grandeur (Azera)',
					'Hyundai H-1 (Starex)',
					'Hyundai HB20',
					'Hyundai i10',
					'Hyundai i20',
					'Hyundai i30',
					'Hyundai i40',
					'Hyundai ix20',
					'Hyundai ix35',
					'Hyundai ix55',
					'Hyundai Lantra',
					'Hyundai Lavita',
					'Hyundai Matrix',
					'Hyundai Maxcruz',
					'Hyundai S-Coupe',
					'Hyundai Santa Fe',
					'Hyundai Santamo',
					'Hyundai Solaris',
					'Hyundai Sonata',
					'Hyundai Terracan',
					'Hyundai Tiburon',
					'Hyundai Trajet',
					'Hyundai Tucson',
					'Hyundai Tuscani',
					'Hyundai Veloster',
					'Hyundai Veracruz',
					'Hyundai Verna',
					'Hyundai XG',
					'Hyundai XG']
			],

			['name'       => 'IFA',
				'categories' => ['Грузовики IFA']
			],

			['name'       => 'IJ',
				'categories' => ['Мототехника IJ',
					'IJ 2125 (Kombi)',
					'IJ 2126 (Oda)',
					'IJ 21261 (Fabula)',
					'IJ 2717',
					'IJ 2717-022',
					'IJ 27175',
					'IJ 49']
			],

			['name'       => 'Ikarbus',
				'categories' => ['Автобусы Ikarbus',
					'Ikarus',
					'Автобусы Ikarus']
			],

			['name'       => 'Indian',
				'categories' => ['Мототехника Indian']
			],

			['name'       => 'Infiniti',
				'categories' => ['Infiniti EX-series',
					'Infiniti FX-series',
					'Infiniti G-series',
					'Infiniti I-series',
					'Infiniti JX-series',
					'Infiniti M-series',
					'Infiniti Q45',
					'Infiniti Q50',
					'Infiniti Q60',
					'Infiniti Q70',
					'Infiniti QX4',
					'Infiniti QX50',
					'Infiniti QX56',
					'Infiniti QX60',
					'Infiniti QX70',
					'Infiniti QX80']
			],

			['name'       => 'International',
				'categories' => ['Грузовики International']
			],

			['name'       => 'Iran Khodro',
				'categories' => ['Iran Khodro Bardo',
					'Iran Khodro Dena',
					'Iran Khodro Runna',
					'Iran Khodro Samand',
					'Iran Khodro Sarir',
					'Iran Khodro Soren']
			],

			['name'       => 'Irbis',
				'categories' => ['Мототехника Irbis']
			],

			['name'       => 'Iron Eagle',
				'categories' => ['Мототехника Iron Eagle']
			],

			['name'       => 'Iron Horse',
				'categories' => ['Мототехника Iron Horse']
			],

			['name'       => 'Isuzu',
				'categories' => ['Грузовики Isuzu',
					'Автобусы Isuzu',
					'Isuzu Aska',
					'Isuzu Axiom',
					'Isuzu Bighorn',
					'Isuzu D-Max',
					'Isuzu Rodeo',
					'Isuzu Trooper',
					'Isuzu VehiCross',
					'Isuzu Wizard']
			],

			['name'       => 'Italjet',
				'categories' => ['Мототехника Italjet']
			],

			['name'       => 'Iveco',
				'categories' => ['Грузовики Iveco',
					'Автобусы Iveco']
			],

			['name'       => 'Iveco Hongyan',
				'categories' => ['Грузовики Iveco Hongyan']
			],

			['name'       => 'Iveco-Ling Ye',
				'categories' => ['Грузовики Iveco-Ling Ye']
			],

			['name'       => 'Iveco-Ural',
				'categories' => ['Грузовики Iveco-Ural']
			],

			['name'       => 'JAC',
				'categories' => ['Грузовики JAC',
					'Автобусы JAC',
					'JAC J2 (Yueyue)',
					'JAC J3 (Tongyue)',
					'JAC J5 (Heyue)',
					'JAC J6 (Heyue RS)',
					'JAC J7 (Binyue)',
					'JAC M1',
					'JAC M5',
					'JAC Pickup',
					'JAC S1 (Rein)',
					'JAC S5 (Eagle)']
			],

			['name'       => 'Jaguar',
				'categories' => ['Jaguar F-Type',
					'Jaguar S-Type',
					'Jaguar X-Type',
					'Jaguar XE',
					'Jaguar XF',
					'Jaguar XJ',
					'Jaguar XK']
			],

			['name'       => 'Janus',
				'categories' => ['Мототехника Janus']
			],

			['name'       => 'Jawa',
				'categories' => ['Мототехника Jawa',
					'Jawa-CZ',
					'Мототехника Jawa-CZ']
			],

			['name'       => 'Jeep',
				'categories' => ['Jeep Cherokee',
					'Jeep Commander',
					'Jeep Compass',
					'Jeep Grand Cherokee',
					'Jeep Liberty (Patriot)',
					'Jeep Rengade',
					'Jeep Wrangler']
			],

			['name'       => 'Jialing',
				'categories' => ['Мототехника Jialing',
					'Jianshe-Yamaha',
					'Мототехника Jianshe-Yamaha']
			],

			['name'       => 'Jinbei',
				'categories' => ['Jinbei Careza',
					'Jinbei FSV',
					'Jinbei Granse']
			],

			['name'       => 'Jinling',
				'categories' => ['Мототехника Jinling']
			],

			['name'       => 'JMC',
				'categories' => ['Грузовики JMC',
					'Мототехника JMC']
			],

			['name'       => 'Johnny Pag',
				'categories' => ['Мототехника Johnny Pag']
			],

			['name'       => 'Joiner',
				'categories' => ['Мототехника Joiner']
			],

			['name'       => 'Jonway',
				'categories' => ['Мототехника Jonway']
			],

			['name'       => 'Jordan Motor',
				'categories' => ['Мототехника Jordan Motor']
			],

			['name'       => 'KAMAZ',
				'categories' => ['Грузовики KAMAZ',
					'Автобусы KAMAZ']
			],

			['name'       => 'Karosa',
				'categories' => ['Автобусы Karosa']
			],

			['name'       => 'Karpaty',
				'categories' => ['Мототехника Karpaty']
			],

			['name'       => 'KAVZ',
				'categories' => ['Автобусы KAVZ']
			],

			['name'       => 'Kawasaki',
				'categories' => ['Мототехника Kawasaki']
			],

			['name'       => 'Kaxa Motos',
				'categories' => ['Мототехника Kaxa Motos']
			],

			['name'       => 'Kayo',
				'categories' => ['Мототехника Kayo']
			],

			['name'       => 'Kazuma',
				'categories' => ['Мототехника Kazuma']
			],

			['name'       => 'Keeway',
				'categories' => ['Мототехника Keeway']
			],

			['name'       => 'Kenworth',
				'categories' => ['Грузовики Kenworth']
			],

			['name'       => 'Khalex',
				'categories' => ['Мототехника Khalex']
			],

			['name'       => 'Kia',
				'categories' => ['Грузовики Kia',
					'Автобусы Kia',
					'Kia Avella',
					'Kia Cadenza',
					'Kia Capital',
					'Kia Carens',
					'Kia Carnival',
					'Kia Cee’d',
					'Kia Cerato',
					'Kia Clarus',
					'Kia Forte',
					'Kia Joice',
					'Kia Lotze',
					'Kia Magentis',
					'Kia Mohave',
					'Kia Opirus',
					'Kia Optima',
					'Kia Picanto',
					'Kia Pride',
					'Kia Quoris',
					'Kia Ray',
					'Kia Retona',
					'Kia Rio',
					'Kia Sedona',
					'Kia Sephia',
					'Kia Shuma',
					'Kia Sorento',
					'Kia Soul',
					'Kia Spectra',
					'Kia Sportage',
					'Kia Venga']
			],

			['name'       => 'King Long',
				'categories' => ['Автобусы King Long']
			],

			['name'       => 'Kinlon',
				'categories' => ['Мототехника Kinlon']
			],

			['name'       => 'Kinroad',
				'categories' => ['Мототехника Kinroad']
			],

			['name'       => 'KRAZ',
				'categories' => ['Грузовики KRAZ']
			],

			['name'       => 'Kreidler',
				'categories' => ['Мототехника Kreidler']
			],

			['name'       => 'KTM',
				'categories' => ['Мототехника KTM']
			],

			['name'       => 'Kubota',
				'categories' => ['Мототехника Kubota']
			],

			['name'       => 'KXD',
				'categories' => ['Мототехника KXD']
			],

			['name'       => 'Kymco',
				'categories' => ['Мототехника Kymco']
			],

			['name'       => 'Lamborghini',
				'categories' => ['Lamborghini Aventador',
					'Lamborghini Diablo',
					'Lamborghini Gallardo',
					'Lamborghini Huracan',
					'Lamborghini Murcielago',
					'Lamborghini Reventon']
			],

			['name'       => 'Lancia',
				'categories' => ['Lancia Dedra',
					'Lancia Delta',
					'Lancia Kappa',
					'Lancia Lybra',
					'Lancia Musa',
					'Lancia Phedra',
					'Lancia Thema',
					'Lancia Thesis',
					'Lancia Voyager',
					'Lancia Ypsilon',
					'Lancia Zeta']
			],

			['name'       => 'Land Rover',
				'categories' => ['Land Rover Defender',
					'Land Rover Discovery',
					'Land Rover Freelander',
					'Land Rover Range Rover',
					'Land Rover Range Rover Evoque',
					'Land Rover Range Rover Sport']
			],

			['name'       => 'LAZ',
				'categories' => ['Автобусы LAZ']
			],

			['name'       => 'LDV',
				'categories' => ['Грузовики LDV']
			],

			['name'       => 'Lebedev Garage',
				'categories' => ['Мототехника Lebedev Garage']
			],

			['name'       => 'Leike',
				'categories' => ['Мототехника Leike']
			],

			['name'       => 'Lexus',
				'categories' => ['Lexus CT-series',
					'Lexus ES-series',
					'Lexus GS-series',
					'Lexus GX-series',
					'Lexus HS-series',
					'Lexus IS-series',
					'Lexus LS-series',
					'Lexus LX-series',
					'Lexus RX-series',
					'Lexus SC-series']
			],

			['name'       => 'LIAZ',
				'categories' => ['Автобусы LIAZ']
			],

			['name'       => 'Lifan',
				'categories' => ['Мототехника Lifan',
					'Lifan Breez (520)',
					'Lifan Cebrium (720)',
					'Lifan Celliya (530)',
					'Lifan Smily (320)',
					'Lifan Solano (620)',
					'Lifan X60']
			],

			['name'       => 'Lincoln',
				'categories' => ['Lincoln Aviator',
					'Lincoln Continental',
					'Lincoln LS',
					'Lincoln Mark LT',
					'Lincoln MKS',
					'Lincoln MKT',
					'Lincoln MKX',
					'Lincoln MKZ',
					'Lincoln Navigator',
					'Lincoln Towncar']
			],

			['name'       => 'Lingben',
				'categories' => ['Мототехника Lingben']
			],

			['name'       => 'Linhai-Yamaha',
				'categories' => ['Мототехника Linhai-Yamaha']
			],

			['name'       => 'LML',
				'categories' => ['Мототехника LML']
			],

			['name'       => 'Lokker',
				'categories' => ['Мототехника Lokker']
			],

			['name'       => 'Loncin',
				'categories' => ['Мототехника Loncin']
			],

			['name'       => 'Lotus',
				'categories' => ['Lotus Elise',
					'Lotus Exige',
					'Lotus Evora']
			],

			['name'       => 'LUAZ',
				'categories' => ['LUAZ 969']
			],

			['name'       => 'Luxgen',
				'categories' => ['Luxgen 5',
					'Luxgen 7',
					'Luxgen U6']
			],

			['name'       => 'Mack',
				'categories' => ['Грузовики Mack']
			],

			['name'       => 'Magirus Deutz',
				'categories' => ['Грузовики Magirus Deutz']
			],

			['name'       => 'Mahindra',
				'categories' => ['Mahindra Marshal',
					'Mahindra MM']
			],

			['name'       => 'Malaguti',
				'categories' => ['Мототехника Malaguti']
			],

			['name'       => 'MAN',
				'categories' => ['Грузовики MAN',
					'Автобусы MAN']
			],

			['name'       => 'Marcopolo',
				'categories' => ['Автобусы Marcopolo']
			],

			['name'       => 'Marz',
				'categories' => ['Автобусы Marz']
			],

			['name'       => 'Masai',
				'categories' => ['Мототехника Masai']
			],

			['name'       => 'Maserati',
				'categories' => ['Maserati 3200 GT',
					'Maserati 4200 GT',
					'Maserati Ghibli',
					'Maserati GranCabrio',
					'Maserati GranSport',
					'Maserati GranTurismo',
					'Maserati MC12',
					'Maserati Quattroporte',
					'Maserati Spyder']
			],

			['name'       => 'Maybach',
				'categories' => ['Maybach 57',
					'Maybach 57 Zeppelin',
					'Maybach 62',
					'Maybach 62 Zeppelin',
					'Maybach 62 Landaulet',
					'Maybach Guard']
			],

			['name'       => 'MAZ',
				'categories' => ['Грузовики MAZ',
					'Автобусы MAZ']
			],

			['name'       => 'Mazda',
				'categories' => ['Грузовики Mazda',
					'Mazda 121',
					'Mazda 2',
					'Mazda 3',
					'Mazda 323',
					'Mazda 5',
					'Mazda 6',
					'Mazda 626',
					'Mazda 929',
					'Mazda Atenza',
					'Mazda Axela',
					'Mazda AZ-Wagon',
					'Mazda B-series',
					'Mazda Biante',
					'Mazda Bongo',
					'Mazda Bongo Friendee',
					'Mazda BT-50',
					'Mazda Capella',
					'Mazda Carol',
					'Mazda CX-5',
					'Mazda CX-7',
					'Mazda CX-9',
					'Mazda Demio',
					'Mazda Familia',
					'Mazda Levante',
					'Mazda Millenia',
					'Mazda MPV',
					'Mazda MX-3',
					'Mazda MX-5',
					'Mazda MX-6',
					'Mazda Premacy',
					'Mazda Proceed',
					'Mazda Protege',
					'Mazda RX 7',
					'Mazda RX 8',
					'Mazda Scrum Wagon',
					'Mazda Tribute',
					'Mazda Verisa',
					'Mazda Xedos 6',
					'Mazda Xedos 9']
			],

			['name'       => 'MAZ-MAN',
				'categories' => ['Грузовики MAZ-MAN']
			],

			['name'       => 'McLaren',
				'categories' => ['McLaren MP4-12C']
			],

			['name'       => 'Megelli',
				'categories' => ['Мототехника Megelli']
			],

			['name'       => 'Mercedes',
				'categories' => ['Грузовики Mercedes',
					'Автобусы Mercedes',
					'Mercedes 190',
					'Mercedes A-class',
					'Mercedes B-class',
					'Mercedes C-class',
					'Mercedes CL-class',
					'Mercedes CLA-class',
					'Mercedes CLC-class',
					'Mercedes CLK-class',
					'Mercedes CLS-class',
					'Mercedes E-class',
					'Mercedes G-class',
					'Mercedes GL-class',
					'Mercedes GLA-class',
					'Mercedes GLK-class',
					'Mercedes M-class',
					'Mercedes R-class',
					'Mercedes S-class',
					'Mercedes SL-class',
					'Mercedes SLK-class',
					'Mercedes SLR McLaren',
					'Mercedes SLS AMG-class',
					'Mercedes V-class',
					'Mercedes Vaneo',
					'Mercedes Viano',
					'Mercedes Vito']
			],

			['name'       => 'Mercury',
				'categories' => ['Mercury Cougar',
					'Mercury Grand Marquis',
					'Mercury Marauder',
					'Mercury Mariner',
					'Mercury Marquis',
					'Mercury Milan',
					'Mercury Mountaineer',
					'Mercury Montego',
					'Mercury Mystique',
					'Mercury Sable',
					'Mercury Topaz',
					'Mercury Villager']
			],

			['name'       => 'MG',
				'categories' => [
					'MG 3',
					'MG 350',
					'MG 5',
					'MG 550',
					'MG 6',
					'MG 750',
					'MG F',
					'MG RV8',
					'MG TF',
					'MG X-Power',
					'MG ZR',
					'MG ZS',
					'MG ZT']
			],

			['name'       => 'Mike-Motors',
				'categories' => ['Мототехника Mike-Motors']
			],

			['name'       => 'Mikilon',
				'categories' => ['Мототехника Mikilon']
			],

			['name'       => 'Mini',
				'categories' => ['Mini Cabrio',
					'Mini Clubman',
					'Mini Clubvan',
					'Mini Countryman',
					'Mini Coupe',
					'Mini Hardtop',
					'Mini Paceman',
					'Mini Roadster']
			],

			[
				'name'       => 'Minsk',
				'categories' => ['Мототехника Minsk']
			],

			['name'       => 'Mitsubishi',
				'categories' => ['Грузовики Mitsubishi',
					'Mitsubishi 3000 GT',
					'Mitsubishi Airtrek',
					'Mitsubishi Aspire',
					'Mitsubishi ASX',
					'Mitsubishi Carisma',
					'Mitsubishi Cedia',
					'Mitsubishi Challenger',
					'Mitsubishi Chariot',
					'Mitsubishi Colt',
					'Mitsubishi Delica',
					'Mitsubishi Diamante',
					'Mitsubishi Dingo',
					'Mitsubishi Dion',
					'Mitsubishi Eclipse',
					'Mitsubishi eK',
					'Mitsubishi Endeavor',
					'Mitsubishi Eterna',
					'Mitsubishi FTO',
					'Mitsubishi Fuzion (Zinger)',
					'Mitsubishi Galant',
					'Mitsubishi Grandis',
					'Mitsubishi GTO',
					'Mitsubishi i',
					'Mitsubishi i-MiEV',
					'Mitsubishi L 200',
					'Mitsubishi L 300',
					'Mitsubishi L400',
					'Mitsubishi Lancer',
					'Mitsubishi Lancer Cargo',
					'Mitsubishi Lancer Evolution',
					'Mitsubishi Legnum',
					'Mitsubishi Libero',
					'Mitsubishi Magna',
					'Mitsubishi Minica',
					'Mitsubishi Mirage',
					'Mitsubishi Montero',
					'Mitsubishi Montero iO',
					'Mitsubishi Montero Sport',
					'Mitsubishi Outlander',
					'Mitsubishi Pajero',
					'Mitsubishi Pajero iO',
					'Mitsubishi Pajero Junior',
					'Mitsubishi Pajero Mini',
					'Mitsubishi Pajero Pinin',
					'Mitsubishi Pajero Sport',
					'Mitsubishi Raider',
					'Mitsubishi RVR',
					'Mitsubishi Sigma',
					'Mitsubishi Space Gear',
					'Mitsubishi Space Runner',
					'Mitsubishi Space Star',
					'Mitsubishi Space Wagon',
					'Mitsubishi Strada',
					'Mitsubishi Toppo',
					'Mitsubishi Town Box']
			],

			['name'       => 'MMZ',
				'categories' => ['Мототехника MMZ']
			],

			['name'       => 'Moskvich',
				'categories' => ['Moskvich 2136',
					'Moskvich 2137',
					'Moskvich 2140',
					'Moskvich 2141',
					'Moskvich 2715',
					'Moskvich 401',
					'Moskvich 402',
					'Moskvich 403',
					'Moskvich 407',
					'Moskvich 408',
					'Moskvich 410',
					'Moskvich 412',
					'Moskvich 423',
					'Moskvich 426',
					'Moskvich Duet',
					'Moskvich Knyaz Vladamir',
					'Moskvich Svyatoqor']
			],

			['name'       => 'Moto Guzzi',
				'categories' => ['Мототехника Moto Guzzi']
			],

			['name'       => 'Moto Morini',
				'categories' => ['Мототехника Moto Morini']
			],

			['name'       => 'Motoland',
				'categories' => ['Мототехника Motoland']
			],

			['name'       => 'Mudan',
				'categories' => ['Автобусы Mudan']
			],

			['name'       => 'MV Agusta',
				'categories' => ['Мототехника MV Agusta']
			],

			['name'       => 'MZKT',
				'categories' => ['Грузовики MZKT']
			],

			['name'       => 'Naveco',
				'categories' => ['Грузовики Naveco']
			],

			['name'       => 'NBLuck',
				'categories' => ['Мототехника NBLuck']
			],

			['name'       => 'NEFAZ',
				'categories' => ['Грузовики NEFAZ',
					'Автобусы NEFAZ']
			],

			['name'       => 'Neman',
				'categories' => ['Автобусы Neman']
			],

			['name'       => 'Neoplan',
				'categories' => ['Автобусы Neoplan']
			],

			['name'       => 'Nexus',
				'categories' => ['Мототехника Nexus']
			],

			['name'       => 'Nissamaran',
				'categories' => ['Мототехника Nissamaran']
			],
			['name'       => 'Nissan',
				'categories' => [
					'Грузовики Nissan',
					'Nissan 100NX',
					'Nissan 180SX',
					'Nissan 200SX',
					'Nissan 240SX',
					'Nissan 300ZX',
					'Nissan 350Z',
					'Nissan 370Z',
					'Nissan AD',
					'Nissan Almera',
					'Nissan Almera Classic',
					'Nissan Almera Tino',
					'Nissan Altima',
					'Nissan Armada',
					'Nissan Avenir',
					'Nissan Bassara',
					'Nissan Bluebird',
					'Nissan Bluebird Sylphy',
					'Nissan Caravan',
					'Nissan Cedric',
					'Nissan Sefiro',
					'Nissan Cima',
					'Nissan Clipper',
					'Nissan Cube',
					'Nissan Datsun',
					'Nissan Dualis',
					'Nissan Elgrand',
					'Nissan Expert',
					'Nissan Fairlady Z',
					'Nissan Fuga',
					'Nissan Gloria',
					'Nissan GT-R',
					'Nissan Juke',
					'Nissan Kix',
					'Nissan Lafesta',
					'Nissan Langley',
					'Nissan Largo',
					'Nissan Latio',
					'Nissan Laurel',
					'Nissan Leaf',
					'Nissan Leopard',
					'Nissan Liberty',
					'Nissan Lucino',
					'Nissan March',
					'Nissan Maxima',
					'Nissan Micra',
					'Nissan Mistral',
					'Nissan Moco',
					'Nissan Murano',
					'Nissan Navara (Frontier)',
					'Nissan Note',
					'Nissan NP300',
					'Nissan NV200',
					'Nissan Otti',
					'Nissan Pathfinder',
					'Nissan Patrol',
					'Nissan Pino',
					'Nissan Pixo',
					'Nissan Prairie',
					'Nissan Presage',
					'Nissan Presea',
					'Nissan President',
					'Nissan Primera',
					'Nissan Pulsar',
					'Nissan Qashqai',
					'Nissan Qashqai +2',
					'Nissan Quest',
					'Nissan Rasheen',
					'Nissan R`nessa',
					'Nissan Rogue',
					'Nissan Roox',
					'Nissan Safari',
					'Nissan Sentra',
					'Nissan Serena',
					'Nissan Silvia',
					'Nissan Skyline',
					'Nissan Stagea',
					'Nissan Sunny',
					'Nissan Teana',
					'Nissan Terrano',
					'Nissan Terrano Regulus',
					'Nissan Tiida',
					'Nissan Tino',
					'Nissan Titan',
					'Nissan Vanette',
					'Nissan Versa',
					'Nissan Wingroad',
					'Nissan X-Trail',
					'Nissan X-Terra']
			],

			['name'       => 'Nitro',
				'categories' => ['Мототехника Nitro']
			],

			['name'       => 'North Benz',
				'categories' => ['Грузовики North Benz']
			],

			['name'       => 'Omaks Motors',
				'categories' => ['Мототехника Omaks Motors']
			],

			['name'       => 'Opel',
				'categories' => ['Грузовики Opel',
					'Opel Adam',
					'Opel Agila',
					'Opel Ampera',
					'Opel Antara',
					'Opel Astra',
					'Opel Calais',
					'Opel Calibra',
					'Opel Campo',
					'Opel Cascada',
					'Opel Combo',
					'Opel Corsa',
					'Opel Frontera',
					'Opel GT',
					'Opel Insignia',
					'Opel Kadett',
					'Opel Meriva',
					'Opel Mokka',
					'Opel Monterey',
					'Opel Omega',
					'Opel Signum',
					'Opel Sintra',
					'Opel Speedster',
					'Opel Tigra',
					'Opel Vectra',
					'Opel Vita',
					'Opel Vivaro',
					'Opel Zafira']
			],

			['name'       => 'Orange County Choppers',
				'categories' => ['Мототехника Orange County Choppers']
			],

			['name'       => 'Orion',
				'categories' => ['Мототехника Orion']
			],

			['name'       => 'Oxobike',
				'categories' => ['Мототехника oxobike']
			],

			['name'       => 'Pagsta',
				'categories' => ['Мототехника Pagsta']
			],

			['name'       => 'Pannonia',
				'categories' => ['Мототехника Pannonia']
			],

			['name'       => 'Patron',
				'categories' => ['Мототехника Patron']
			],

			['name'       => 'PAZ',
				'categories' => ['Автобусы PAZ']
			],

			['name'       => 'PCW',
				'categories' => ['Мототехника PCW']
			],

			['name'       => 'Peterbilt',
				'categories' => ['Грузовики Peterbilt']
			],

			['name'       => 'Peugeot',
				'categories' => ['Грузовики Peugeot',
					'Мототехника Peugeot',
					'Peugeot 1007',
					'Peugeot 106',
					'Peugeot 107',
					'Peugeot 2008',
					'Peugeot 205',
					'Peugeot 206',
					'Peugeot 207',
					'Peugeot 208',
					'Peugeot 3008',
					'Peugeot 301',
					'Peugeot 306',
					'Peugeot 307',
					'Peugeot 308',
					'Peugeot 4007',
					'Peugeot 4008',
					'Peugeot 405',
					'Peugeot 406',
					'Peugeot 407',
					'Peugeot 408',
					'Peugeot 5008',
					'Peugeot 508',
					'Peugeot 605',
					'Peugeot 607',
					'Peugeot 806',
					'Peugeot 807',
					'Peugeot Bipper',
					'Peugeot Expert',
					'Peugeot ION',
					'Peugeot Pars',
					'Peugeot Partner',
					'Peugeot RCZ']
			],

			['name'       => 'PGO',
				'categories' => ['Мототехника PGO']
			],

			['name'       => 'Piaggio',
				'categories' => ['Мототехника Piaggio']
			],

			['name'       => 'Pitmoto',
				'categories' => ['Мототехника Pitmoto']
			],

			['name'       => 'Pitrace',
				'categories' => ['Мототехника Pitrace']
			],

			['name'       => 'Pitsterpro',
				'categories' => ['Мототехника Pitsterpro']
			],

			['name'       => 'Plymouth',
				'categories' => ['Plymouth Acclaim',
					'Plymouth Breeze',
					'Plymouth Laser',
					'Plymouth Neon',
					'Plymouth Sundance',
					'Plymouth Voyager']
			],

			['name'       => 'Pocket Bike',
				'categories' => ['Мототехника Pocket Bike']
			],

			['name'       => 'Polar Fox',
				'categories' => ['Мототехника Polar Fox']
			],

			['name'       => 'Polaris',
				'categories' => ['Мототехника Polaris']
			],

			['name'       => 'Polini',
				'categories' => ['Мототехника Polini']
			],

			['name'       => 'Pontiac',
				'categories' => ['Pontiac Aztek',
					'Pontiac Bonneville',
					'Pontiac Firebird',
					'Pontiac G6',
					'Pontiac Grand Am',
					'Pontiac Grand Prix',
					'Pontiac GTO',
					'Pontiac Montana',
					'Pontiac Solstice',
					'Pontiac Sunfire',
					'Pontiac Trans Sport',
					'Pontiac Vibe']
			],

			['name'       => 'Pony Motors',
				'categories' => ['Мототехника Pony Motors']
			],
			['name'       => 'Porsche',
				'categories' => [
					'Porsche 911',
					'Porsche 928',
					'Porsche 968',
					'Porsche Boxster',
					'Porsche Carrera GT',
					'Porsche Cayenne',
					'Porsche Cayman',
					'Porsche Macan',
					'Porsche Panamera']
			],

			['name'       => 'Proton',
				'categories' => [
					'Proton Exora',
					'Proton Gen-2',
					'Proton Inspira',
					'Proton Juara',
					'Proton Persona',
					'Proton Perdana',
					'Proton Preve',
					'Proton Saga',
					'Proton Satria',
					'Proton Suprima S',
					'Proton Waja',
					'Proton Wira']
			],

			['name'       => 'Pskovskaya Mekhanika',
				'categories' => ['Мототехника Pskovskaya Mekhanika']
			],

			['name'       => 'Puch',
				'categories' => ['Мототехника Puch']
			],

			['name'       => 'Qingqi',
				'categories' => ['Мототехника Qingqi']
			],

			['name'       => 'Quadro',
				'categories' => ['Мототехника Quadro']
			],

			['name'       => 'Quadreider',
				'categories' => ['Мототехника Quadreider']
			],

			['name'       => 'Racer',
				'categories' => ['Мототехника Racer']
			],

			['name'       => 'Rapira',
				'categories' => ['Мототехника Rapira']
			],

			['name'       => 'Regal Raptor',
				'categories' => ['Мототехника Regal Raptor']
			],

			['name'       => 'Reggy',
				'categories' => ['Мототехника Reggy']
			],

			['name'       => 'Renault',
				'categories' => ['Грузовики Renault',
					'Renault 11',
					'Renault 12',
					'Renault 19',
					'Renault 21',
					'Renault 25',
					'Renault Avantime',
					'Renault Clio',
					'Renault Duster',
					'Renault Espace',
					'Renault Fluence',
					'Renault Kangoo',
					'Renault Koleos',
					'Renault Laguna',
					'Renault Latitude',
					'Renault Logan',
					'Renault Megane',
					'Renault Modus',
					'Renault Safrane',
					'Renault Sandero',
					'Renault Scenic',
					'Renault Symbol',
					'Renault Trafic',
					'Renault Twingo',
					'Renault Vel Satis',
					'Renault Wind',
					'Renault Zoe']
			],

			['name'       => 'Renli',
				'categories' => ['Мототехника Renli']
			],

			['name'       => 'Rewaco',
				'categories' => ['Мототехника Rewaco']
			],

			['name'       => 'Rieju',
				'categories' => ['Мототехника Rieju']
			],

			['name'       => 'Riga',
				'categories' => ['Мототехника Riga']
			],

			['name'       => 'ROAZ',
				'categories' => ['Автобусы ROAZ']
			],

			['name'       => 'Robur',
				'categories' => ['Грузовики Robur']
			],

			['name'       => 'Roewe',
				'categories' => ['Roewe 350',
					'Roewe 550',
					'Roewe 750',
					'Roewe 950',
					'Roewe E50',
					'Roewe MG3',
					'Roewe MG5',
					'Roewe MG6',
					'Roewe MG7',
					'Roewe MGTF',
					'Roewe W5']
			],

			['name'       => 'Rokon',
				'categories' => ['Мототехника Rokon']
			],

			['name'       => 'Rolls-Royce',
				'categories' => ['Rolls-Royce Corniche',
					'Rolls-Royce Flying Spur',
					'Rolls-Royce Ghost',
					'Rolls-Royce Park Ward',
					'Rolls-Royce Phantom',
					'Rolls-Royce Silver Seraph',
					'Rolls-Royce Silver Spur',
					'Rolls-Royce Wraith']
			],

			['name'       => 'Routemaster',
				'categories' => ['Автобусы Routemaster']
			],

			['name'       => 'Rover',
				'categories' => ['Rover 100',
					'Rover 200',
					'Rover 25',
					'Rover 400',
					'Rover 416',
					'Rover 45',
					'Rover 600',
					'Rover 75',
					'Rover 800',
					'Rover CityRover',
					'Rover Metro',
					'Rover Mini',
					'Rover Streetwise']
			],

			['name'       => 'Royal Enfield',
				'categories' => ['Мототехника Royal Enfield']
			],

			['name'       => 'Rusich',
				'categories' => ['Грузовики Rusich']
			],

			['name'       => 'Russkaya Mekhanika',
				'categories' => ['Мототехника Russkaya Mekhanika']
			],

			['name'       => 'Saab',
				'categories' => ['Saab 9-2X',
					'Saab 9-3',
					'Saab 9-4X',
					'Saab 9-5',
					'Saab 9-7X',
					'Saab 900',
					'Saab 9000']
			],

			['name'       => 'Sachs Bikes',
				'categories' => ['Мототехника Sachs Bikes']
			],

			['name'       => 'Sagitta',
				'categories' => ['Мототехника Sagitta']
			],

			['name'       => 'Saipa',
				'categories' => ['Saipa 111',
					'Saipa 132',
					'Saipa 141',
					'Saipa Saba',
					'Saipa Tiba']
			],

			['name'       => 'Saturn',
				'categories' => ['Saturn L-series',
					'Saturn S-series',
					'Saturn Aura',
					'Saturn Astra',
					'Saturn ION',
					'Saturn Outlook',
					'Saturn Relay',
					'Saturn SC',
					'Saturn SKY',
					'Saturn VUE']
			],

			['name'       => 'Saxon',
				'categories' => ['Мототехника Saxon']
			],

			['name'       => 'Scania',
				'categories' => ['Грузовики Scania',
					'Автобусы Scania']
			],

			['name'       => 'Scarabeo',
				'categories' => ['Мототехника Scarabeo']
			],

			['name'       => 'Scion',
				'categories' => ['Scion tC',
					'Scion xA',
					'Scion xB']
			],

			['name'       => 'SEAT',
				'categories' => ['SEAT Alhambra',
					'SEAT Altea',
					'SEAT Arosa',
					'SEAT Cordoba',
					'SEAT Exeo',
					'SEAT Ibiza',
					'SEAT Inca',
					'SEAT Leon',
					'SEAT Toledo',
					'SEAZ',
					'SEAZ Oka']
			],

			['name'       => 'Senke',
				'categories' => ['Мототехника Senke']
			],

			['name'       => 'Setra',
				'categories' => ['Автобусы Setra']
			],

			['name'       => 'Shaanxi',
				'categories' => ['Грузовики Shaanxi']
			],

			['name'       => 'Shaanxi-MAN',
				'categories' => ['Грузовики Shaanxi-MAN']
			],

			['name'       => 'Shacman',
				'categories' => ['Грузовики Shacman']
			],

			['name'       => 'Shaolin',
				'categories' => ['Автобусы Shaolin']
			],

			['name'       => 'Shen Long',
				'categories' => ['Автобусы Shen Long']
			],

			['name'       => 'Sherco',
				'categories' => ['Мототехника Sherco']
			],

			['name'       => 'Shineray',
				'categories' => ['Мототехника Shineray',
					'Shineray A7']
			],

			['name'       => 'Shuang Huan',
				'categories' => ['Shuang Huan Noble',
					'Shuang Huan SCEO']
			],

			['name'       => 'Shuchi',
				'categories' => ['Автобусы Shuchi']
			],

			['name'       => 'Simbel',
				'categories' => ['Мототехника Simbel']
			],

			['name'       => 'Simson',
				'categories' => ['Мототехника Simson']
			],

			['name'       => 'Sinotruck',
				'categories' => ['Грузовики Sinotruck']
			],

			['name'       => 'Sisu',
				'categories' => ['Грузовики Sisu']
			],

			['name'       => 'Skoda',
				'categories' => ['Skoda Citigo',
					'Skoda Fabia',
					'Skoda Felicia',
					'Skoda Octavia',
					'Skoda Praktik',
					'Skoda Rapid',
					'Skoda Roomster',
					'Skoda Superb',
					'Skoda Yeti',
					'Skoda LIAZ',
					'Грузовики Skoda LIAZ']
			],

			['name'       => 'Skygo',
				'categories' => ['Мототехника Skygo']
			],

			['name'       => 'Smart',
				'categories' => ['Smart City',
					'Smart Forfour',
					'Smart Fortwo',
					'Smart Roadster']
			],

			['name'       => 'Sonik',
				'categories' => ['Мототехника Sonik']
			],

			['name'       => 'Ssang Yong',
				'categories' => ['Грузовики Ssang Yong',
					'Автобусы Ssang Yong']
			],

			['name'       => 'Ssang Yong Actyon',
				'categories' => ['Ssang Yong Actyon Sports',
					'Ssang Yong Chairman',
					'Ssang Yong Korando',
					'Ssang Yong Kyron',
					'Ssang Yong Musso',
					'Ssang Yong Rexton',
					'Ssang Yong Rodius',
					'Ssang Yong Stavic']
			],

			['name'       => 'Stels',
				'categories' => ['Мототехника Stels']
			],

			['name'       => 'Sterling',
				'categories' => ['Грузовики Sterling']
			],

			['name'       => 'Stingray',
				'categories' => ['Мототехника Stingray']
			],

			['name'       => 'Strom',
				'categories' => ['Мототехника Strom']
			],

			['name'       => 'Subaru',
				'categories' => ['Subaru Baja',
					'Subaru BRZ',
					'Subaru Exiga',
					'Subaru Forester',
					'Subaru Impreza',
					'Subaru Justy',
					'Subaru Legacy',
					'Subaru Outback',
					'Subaru Pleo',
					'Subaru R1',
					'Subaru R2',
					'Subaru Stella',
					'Subaru Trezia',
					'Subaru Tribeca',
					'Subaru Vivio',
					'Subaru XV']
			],

			['name'       => 'Suzuki',
				'categories' => ['Грузовики Suzuki',
					'Мототехника Suzuki',
					'Suzuki Aerio',
					'Suzuki Alto',
					'Suzuki Baleno (Esteem)',
					'Suzuki Celerio',
					'Suzuki Cervo',
					'Suzuki Cultus',
					'Suzuki Cultus Crescent',
					'Suzuki Ertiga',
					'Suzuki Escudo',
					'Suzuki Every',
					'Suzuki Forenza',
					'Suzuki Grand Vitara',
					'Suzuki Ignis',
					'Suzuki Jimny',
					'Suzuki Kei',
					'Suzuki Kizashi',
					'Suzuki Landy',
					'Suzuki Liana',
					'Suzuki MR Wagon',
					'Suzuki Palette',
					'Suzuki Reno',
					'Suzuki Samurai',
					'Suzuki Sidekick',
					'Suzuki Solio',
					'Suzuki Spacia',
					'Suzuki Splash',
					'Suzuki Swift',
					'Suzuki SX4',
					'Suzuki Verona',
					'Suzuki Vitara',
					'Suzuki Wagon R',
					'Suzuki Wagon R+',
					'Suzuki X-90',
					'Suzuki XL7']
			],

			['name'       => 'SYM',
				'categories' => ['Мототехника SYM']
			],

			['name'       => 'TAM',
				'categories' => ['Автобусы TAM']
			],

			['name'       => 'TAGAZ',
				'categories' => ['Грузовики TAGAZ',
					'TAGAZ Aquila',
					'TAGAZ C10',
					'TAGAZ C190',
					'TAGAZ Road Partner',
					'TAGAZ Tager',
					'TAGAZ Vega']
			],

			['name'       => 'Tata',
				'categories' => ['Грузовики Tata',
					'Tata Daewoo',
					'Грузовики Tata Daewoo']
			],

			['name'       => 'Tatra',
				'categories' => ['Грузовики Tatra']
			],

			['name'       => 'Temsa',
				'categories' => ['Автобусы Temsa']
			],

			['name'       => 'Terberg',
				'categories' => ['Грузовики Terberg']
			],

			['name'       => 'Tesla',
				'categories' => ['Tesla Model S']
			],

			['name'       => 'Tianma',
				'categories' => ['Tianma Century',
					'Tianye',
					'Tianye Admiral']
			],

			['name'       => 'Tiema',
				'categories' => ['Грузовики Tiema']
			],

			['name'       => 'Titan',
				'categories' => ['Грузовики Titan',
					'Мототехника Titan']
			],

			['name'       => 'TM',
				'categories' => ['Мототехника TM']
			],

			['name'       => 'TMZ',
				'categories' => ['Мототехника TMZ']
			],

			['name'       => 'Tofas',
				'categories' => ['Tofas Dogan',
					'Tofas Kartal',
					'Tofas Sahin']
			],

			['name'       => 'Tomos',
				'categories' => ['Мототехника Tomos']
			],

			['name'       => 'Tonar',
				'categories' => ['Грузовики Tonar']
			],

			['name'       => 'Tornado',
				'categories' => ['Мототехника Tornado']
			],

			['name'       => 'Toyota',
				'categories' => ['Грузовики Toyota',
					'Автобусы Toyota',
					'Toyota 4-Runner',
					'Toyota Allex',
					'Toyota Allion',
					'Toyota Alphard',
					'Toyota Altezza',
					'Toyota Aristo',
					'Toyota Aurion',
					'Toyota Auris',
					'Toyota Avalon',
					'Toyota Avanza',
					'Toyota Avensis',
					'Toyota Avensis Verso',
					'Toyota Aygo',
					'Toyota bB',
					'Toyota Belta',
					'Toyota Blade',
					'Toyota Brevis',
					'Toyota Caldina',
					'Toyota Cami',
					'Toyota Camry',
					'Toyota Camry Solara',
					'Toyota Carina',
					'Toyota Carina ED',
					'Toyota Cavalier',
					'Toyota Celica',
					'Toyota Celsior',
					'Toyota Chaser',
					'Toyota Corolla',
					'Toyota Corolla Axio',
					'Toyota Corolla Ceres',
					'Toyota Corolla Levin',
					'Toyota Corolla Rumion',
					'Toyota Corolla Verso (Spacio)',
					'Toyota Corona',
					'Toyota Corsa',
					'Toyota Cressida',
					'Toyota Cresta',
					'Toyota Crown',
					'Toyota Crown Majesta',
					'Toyota Curren',
					'Toyota Cynos',
					'Toyota Duet',
					'Toyota Echo',
					'Toyota Estima',
					'Toyota EXiV',
					'Toyota FJ Cruiser',
					'Toyota Fortuner',
					'Toyota Funcargo',
					'Toyota Gaia',
					'Toyota Granvia',
					'Toyota GT 86',
					'Toyota Harrier',
					'Toyota Hiace',
					'Toyota Highlander',
					'Toyota Hilux',
					'Toyota Hilux Surf',
					'Toyota Innova',
					'Toyota Ipsum',
					'Toyota iQ',
					'Toyota ISis',
					'Toyota Ist',
					'Toyota Kluger',
					'Toyota Land Cruiser',
					'Toyota Land Cruiser Prado',
					'Toyota LiteAce',
					'Toyota Mark II',
					'Toyota Mark X',
					'Toyota Mark X ZiO',
					'Toyota Matrix',
					'Toyota Mega Cruiser',
					'Toyota MR2',
					'Toyota MR-S',
					'Toyota Nadia',
					'Toyota Noah',
					'Toyota Opa',
					'Toyota Paceo',
					'Toyota Passo',
					'Toyota Passo Sette',
					'Toyota Picnic',
					'Toyota Platz',
					'Toyota Porte',
					'Toyota Premio',
					'Toyota Previa',
					'Toyota Prius',
					'Toyota Prius C (Aqua)',
					'Toyota Prius V',
					'Toyota Probox',
					'Toyota Progres',
					'Toyota Pronard',
					'Toyota Ractis',
					'Toyota Raum',
					'Toyota RAV 4',
					'Toyota Regius',
					'Toyota RegiusAce',
					'Toyota Rush',
					'Toyota Sai',
					'Toyota Scepter',
					'Toyota Sequoia',
					'Toyota Sera',
					'Toyota Sienna',
					'Toyota Sienta',
					'Toyota Soarer',
					'Toyota Soluna',
					'Toyota Sparky',
					'Toyota Sprinter',
					'Toyota Sprinter Carib',
					'Toyota Sprinter Marino',
					'Toyota Sprinter Trueno',
					'Toyota Starlet',
					'Toyota Succeed',
					'Toyota Supra',
					'Toyota Tacoma',
					'Toyota Tercel',
					'Toyota TownAce',
					'Toyota Tundra',
					'Toyota Urban Cruiser',
					'Toyota Vanguard',
					'Toyota Vellfire',
					'Toyota Venza',
					'Toyota Verossa',
					'Toyota Verso',
					'Toyota Verso-S',
					'Toyota Vios',
					'Toyota Vista',
					'Toyota Vitz',
					'Toyota Voltz',
					'Toyota Voxy',
					'Toyota WiLL Cypha (Will VC)',
					'Toyota Will Vi',
					'Toyota Will VS',
					'Toyota Windom',
					'Toyota Wish',
					'Toyota Yaris',
					'Toyota Yaris Verso',
					'Toyota Zelas']
			],

			['name'       => 'Triumph',
				'categories' => ['Мототехника Triumph']
			],

			['name'       => 'Tula',
				'categories' => ['Мототехника Tula']
			],

			['name'       => 'TVS',
				'categories' => ['Мототехника TVS']
			],

			['name'       => 'UAZ',
				'categories' => ['Грузовики UAZ',
					'UAZ 2206',
					'UAZ 23602 Cargo',
					'UAZ 3151',
					'UAZ 3153',
					'UAZ 3159 Bars',
					'UAZ 3160',
					'UAZ 3162 Simbir',
					'UAZ 3303',
					'UAZ 3741',
					'UAZ 3909',
					'UAZ 452',
					'UAZ 469',
					'UAZ Patriot',
					'UAZ Hunter',
					'UAZ Pickup']
			],

			['name'       => 'UMC',
				'categories' => ['Мототехника UMC']
			],

			['name'       => 'Ural',
				'categories' => ['Грузовики Ural',
					'Автобусы Ural',
					'Мототехника Ural']
			],

			['name'       => 'Van Hool',
				'categories' => ['Автобусы Van Hool']
			],

			['name'       => 'VAZ',
				'categories' => ['VAZ 1111 Oka',
					'VAZ 2101',
					'VAZ 2102',
					'VAZ 2103',
					'VAZ 2104',
					'VAZ 2105',
					'VAZ 2106',
					'VAZ 2107',
					'VAZ 2108',
					'VAZ 2109',
					'VAZ 21099',
					'VAZ 2110',
					'VAZ 2111',
					'VAZ 2112',
					'VAZ 21123',
					'VAZ 2113',
					'VAZ 2114',
					'VAZ 2115',
					'VAZ 2120 Nadejda',
					'VAZ 2121',
					'VAZ 2123',
					'VAZ 2129',
					'VAZ 2131',
					'VAZ 2328',
					'VAZ 2329',
					'VAZ Granta',
					'VAZ Kalina',
					'VAZ Largus',
					'VAZ Niva',
					'VAZ Priora',
					'VAZ Tarzan']
			],

			['name'       => 'Veloci',
				'categories' => ['Мототехника Veloci']
			],

			['name'       => 'Venta',
				'categories' => ['Мототехника Venta']
			],

			['name'       => 'Vento',
				'categories' => ['Мототехника Vento']
			],

			['name'       => 'Verkhovina',
				'categories' => ['Мототехника Verkhovina']
			],

			['name'       => 'Vespa',
				'categories' => ['Мототехника Vespa']
			],

			['name'       => 'Victory',
				'categories' => ['Мототехника Victory']
			],

			['name'       => 'Viper',
				'categories' => ['Мототехника Viper']
			],

			['name'       => 'Voljanin',
				'categories' => ['Автобусы Voljanin']
			],

			['name'       => 'Volkswagen',
				'categories' => ['Грузовики Volkswagen',
					'Автобусы Volkswagen',
					'Volkswagen Amarok',
					'Volkswagen Beetle',
					'Volkswagen Bora',
					'Volkswagen Caddy',
					'Volkswagen California',
					'Volkswagen Caravelle',
					'Volkswagen Citi Golf',
					'Volkswagen Corrado',
					'Volkswagen Eos',
					'Volkswagen Fox',
					'Volkswagen Gol',
					'Volkswagen Golf',
					'Volkswagen Golf Plus',
					'Volkswagen Golf Sportsvan',
					'Volkswagen Jetta',
					'Volkswagen Lupo',
					'Volkswagen Multivan',
					'Volkswagen New Beetle',
					'Volkswagen Parati',
					'Volkswagen Passat',
					'Volkswagen Passat CC',
					'Volkswagen Phaeton',
					'Volkswagen Pointer',
					'Volkswagen Polo',
					'Volkswagen Routan',
					'Volkswagen Saveiro',
					'Volkswagen Scirocco',
					'Volkswagen Sharan',
					'Volkswagen Suran',
					'Volkswagen Tiguan',
					'Volkswagen Touareg',
					'Volkswagen Touran',
					'Volkswagen Transporter',
					'Volkswagen Up',
					'Volkswagen Vento',
					'Volkswagen XL1']
			],

			['name'       => 'Volvo',
				'categories' => ['Грузовики Volvo',
					'Автобусы Volvo',
					'Volvo 440',
					'Volvo 460',
					'Volvo 740',
					'Volvo 760',
					'Volvo 780',
					'Volvo 850',
					'Volvo 940',
					'Volvo 960',
					'Volvo C 30',
					'Volvo C 70',
					'Volvo S 40',
					'Volvo S 60',
					'Volvo S 70',
					'Volvo S 80',
					'Volvo S 90',
					'Volvo V 40',
					'Volvo V 50',
					'Volvo V 60',
					'Volvo V 70',
					'Volvo V 90',
					'Volvo XC 60',
					'Volvo XC 70',
					'Volvo XC 90']
			],

			['name'       => 'Von Dutch',
				'categories' => ['Мототехника Von Dutch']
			],

			['name'       => 'Vortex',
				'categories' => ['Vortex Corda',
					'Vortex Estina',
					'Vortex Tingo']
			],

			['name'       => 'Voskhod',
				'categories' => ['Мототехника Voskhod']
			],

			['name'       => 'Vyatka',
				'categories' => ['Мототехника Vyatka']
			],

			['name'       => 'Wels',
				'categories' => ['Мототехника Wels']
			],

			['name'       => 'WYP-Motor',
				'categories' => ['Мототехника WYP-Motor']
			],

			['name'       => 'Xin Kai',
				'categories' => ['Xin Kai SR-V X3',
					'Xin Kai SUV X3',
					'Xin Kai Pickup X3']
			],

			['name'       => 'XMotos',
				'categories' => ['Мототехника XMotos']
			],

			['name'       => 'Yamaha',
				'categories' => ['Мототехника Yamaha']
			],

			['name'       => 'Yamasaki',
				'categories' => ['Мототехника Yamasaki']
			],

			['name'       => 'Yarovit',
				'categories' => ['Грузовики Yarovit']
			],

			['name'       => 'YCF',
				'categories' => ['Мототехника YCF']
			],

			['name'       => 'Yiben',
				'categories' => ['Мототехника Yiben']
			],

			['name'       => 'Yinxiang',
				'categories' => ['Мототехника Yinxiang']
			],

			['name'       => 'Yuejin',
				'categories' => ['Грузовики Yuejin']
			],

			['name'       => 'Yutong',
				'categories' => ['Автобусы Yutong']
			],

			['name'       => 'ZAZ',
				'categories' => ['ZAZ 965',
					'ZAZ 966',
					'ZAZ 968',
					'ZAZ 1103 Slavuta',
					'ZAZ 1102 Tavriya',
					'ZAZ 1105 Dana',
					'ZAZ Chance',
					'ZAZ Forza',
					'ZAZ Lanos',
					'ZAZ Sens',
					'ZAZ Vida']
			],

			['name'       => 'Zhong Tong',
				'categories' => ['Автобусы Zhong Tong']
			],

			['name'       => 'ZID',
				'categories' => ['Мототехника ZID']
			],

			['name'       => 'ZIL',
				'categories' => ['Грузовики ZIL',
					'Автобусы ZIL']
			],

			['name'       => 'ZIM',
				'categories' => ['Мототехника ZIM']
			],

			['name'       => 'Zonda',
				'categories' => ['Автобусы Zonda']
			],

			['name'       => 'Zongshen',
				'categories' => ['Мототехника Zongshen']
			],

			[
				'name'       => 'Zontes',
				'categories' => ['Мототехника Zontes']
			],

			[
				'name'       => 'ZX',
				'categories' => [
					'ZX Admiral',
					'ZX Grand Tiger',
					'ZX Landmark'
				]
			]
		];

		foreach ($categories as $category) {
			Log::debug($category['name']);
			$parent = Category::create(['title' => $category['name']]);
			foreach($category['categories'] as $childCategory) {
				Log::debug("Child $childCategory");
				Category::create(['title' => $childCategory, 'parent_id' => $parent->id]);
			}
		}
	}

}