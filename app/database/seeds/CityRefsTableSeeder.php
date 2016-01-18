<?php

class CityRefsTableSeeder extends Seeder {

	public function run() {

		CityRef::truncate();

		$cities = [

			[ 'az' => 'digər', 'ru' => 'Другой'],

			[ 'az' => 'Astara', 'ru' => 'Астара'],

			[ 'az' =>'Ağcabədi', 'ru' => 'Агджабеди'],

			[ 'az' =>'Ağdam',  'ru' => 'Агдам'],

			[ 'az' => 'Ağdaş', 'ru' =>  'Агдаш'],

			[ 'az' =>'Ağdərə', 'ru' =>  'Агдере'],

			[ 'az' =>'Ağstafa', 'ru' =>  'Акстафа'],

			[ 'az' =>'Ağsu', 'ru' =>  'Аксу'],

			[ 'az' =>'Bakı', 'ru' => 'Баку'],

			[ 'az' =>'Balakən', 'ru' => 'Белокан'],

			[ 'az' =>'Beyləqan', 'ru' => 'Бейлаган'],

			[ 'az' =>'Biləsuvar', 'ru' => 'Билесувар'],

			[ 'az' =>'Bərdə' , 'ru' => 'Барда'],

			[ 'az' =>'Culfa' , 'ru' => 'Джульфа'],

			[ 'az' =>'Cəbrayıl' , 'ru' => 'Джабраил'],

			[ 'az' =>'Cəlilabad' , 'ru' => 'Джалилабад'],

			[ 'az' =>'Daşkəsən' , 'ru' => 'Дашкесан'],

			[ 'az' =>'Dəliməmmədli' , 'ru' => 'Делимамедли'],

			[ 'az' =>'Füzuli' , 'ru' => 'Физули'],

			[ 'az' =>'Goranboy' , 'ru' => 'Гёранбой'],

			[ 'az' =>'Göygöl' , 'ru' => 'Гёйгёль'],

			[ 'az' =>'Göytəpə' , 'ru' => 'Гёйтепе'],

			[ 'az' =>'Göyçay' , 'ru' => 'Гёйчай'],

			[ 'az' =>'Gədəbəy' , 'ru' => 'Гедабек'],

			[ 'az' =>'Gəncə' , 'ru' => 'Гянджа'],

			[ 'az' =>'Horadiz' , 'ru' => 'Горадиз'],

			[ 'az' =>'İmişli' , 'ru' => 'Имишли'],

			[ 'az' =>'İsmayıllı' , 'ru' => 'Исмаиллы'],

			[ 'az' =>'Kürdəmir' , 'ru' => 'Кюрдамир'],

			[ 'az' =>'Kəlbəcər' , 'ru' => 'Кельбаджар'],

			[ 'az' =>'Laçın' , 'ru' => 'Лачин'],

			[ 'az' =>'Lerik' , 'ru' => 'Лерик'],

			[ 'az' =>'Liman' , 'ru' => 'Лиман'],

			[ 'az' =>'Lənkəran' , 'ru' => 'Ленкорань'],

			[ 'az' =>'Masallı' , 'ru' => 'Масаллы'],

			[ 'az' =>'Mingəçevir' , 'ru' => 'Мингечаур'],

			[ 'az' =>'Naftalan' , 'ru' => 'Нафталан'],

			[ 'az' =>'Naxçıvan' , 'ru' => 'Нихичевань'],

			[ 'az' =>'Neftçala' , 'ru' => 'Нефтчала'],

			[ 'az' =>'Ordubad' , 'ru' => 'Ордубад'],

			[ 'az' =>'Oğuz' , 'ru' => 'Огуз'],

			[ 'az' =>'Qax' , 'ru' => 'Кахи'],

			[ 'az' =>'Qazax' , 'ru' => 'Казах'],

			[ 'az' =>'Qobustan' , 'ru' => 'Гобустан'],

			[ 'az' =>'Quba' , 'ru' => 'Губа'],

			[ 'az' =>'Qubadlı' , 'ru' => 'Губадлы'],

			[ 'az' =>'Qusar' , 'ru' => 'Гусар'],

			[ 'az' =>'Qəbələ' , 'ru' => 'Габала'],

			[ 'az' =>'Saatlı' , 'ru' => 'Саатлы'],

			[ 'az' =>'Sabirabad' , 'ru' => 'Сабирабад'],

			[ 'az' =>'Salyan' , 'ru' => 'Сальян'],

			[ 'az' =>'Samux' , 'ru' => 'Самух'],

			[ 'az' =>'Siyəzən' , 'ru' => 'Сиязань'],

			[ 'az' =>'Sumqayıt' , 'ru' => 'Сумгаит'],

			[ 'az' =>'Tovuz' , 'ru' => 'Товуз'],

			[ 'az' =>'Tərtər' , 'ru' => 'Тер-Тер'],

			[ 'az' =>'Ucar' , 'ru' => 'Уджар'],

			[ 'az' =>'Xankəndi' , 'ru' => 'Ханкенди'],

			[ 'az' =>'Xaçmaz' , 'ru' => 'Хачмаз'],

			[ 'az' =>'Xocalı' , 'ru' => 'Ходжалы'],

			[ 'az' =>'Xocavənd' , 'ru' => 'Ходжавенд'],

			[ 'az' =>'Xudat' , 'ru' => 'Худат'],

			[ 'az' =>'Xırdalan' , 'ru' => 'Хырдалан'],

			[ 'az' =>'Xızı' , 'ru' => 'Хызы'],

			[ 'az' =>'Yardımlı' , 'ru' => 'Ярдымлы'],

			[ 'az' =>'Yevlax' , 'ru' => 'Евлах'],

			[ 'az' =>'Zaqatala' , 'ru' => 'Загатала'],

			[ 'az' =>'Zəngilan' , 'ru' => 'Зенгилан'],

			[ 'az' =>'Zərdab' , 'ru' => 'Зардаб'],

			[ 'az' =>'Şabran' , 'ru' => 'Шабран'],

			[ 'az' =>'Şahbuz' , 'ru' => 'Шахбуз'],

			[ 'az' =>'Şamaxı' , 'ru' => 'Шемаха'],

			[ 'az' =>'Şirvan' , 'ru' => 'Ширван'],

			[ 'az' =>'Şuşa' , 'ru' => 'Шуша'],

			[ 'az' =>'Şəki' , 'ru' => 'Шеки'],

			[ 'az' =>'Şəmkir' , 'ru' => 'Шамкир'],

			[ 'az' =>	'Şərur' , 'ru' => 'Шарур'],

		];

		foreach ($cities as $city) {
			CityRef::create($city);
		}

	}

}