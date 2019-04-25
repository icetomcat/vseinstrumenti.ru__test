<?php

require_once 'startup.php';

$app = new App();

foreach (scandir(__DIR__ . "/../private/Model/") as $res)
{

	if (pathinfo($res, PATHINFO_EXTENSION) == "php")
	{
		$class = "Model\\" . pathinfo($res, PATHINFO_FILENAME);
		if (class_exists($class))
		{
			$schema = $class::getSchema();
			//$app->db()->delete(["table" => $schema->getName()])->execute();
			$app->db()->alterTable($schema);
		}
	}
}

srand(time());


$region_controller = new Controller\Region($app);

$region_controller->saveModel(Model\Region::create(["name" => "Санкт-Петербург", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Уфа", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Нижний Новгород", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Владимир", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Кострома", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Екатеринбург", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Ковров", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Воронеж", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Самара", "period" => rand(2, 8)]));
$region_controller->saveModel(Model\Region::create(["name" => "Астрахань", "period" => rand(2, 8)]));


$courier_controller = new \Controller\Courier($app);

$courier_controller->saveModel(Model\Courier::create(["full_name" => "Август Комиссаров"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Кирилл Калашников"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Бенедикт Медведев"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Иван Гаскаров"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Игнат Медведев"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Аркадий Соловьёв"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Николай Сталин"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Кузьма Максимов"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Савва Плечистов"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Велимир Баранов"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Пётр Суворов"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Леонтий Орехов"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Виссарион Михайлов"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Трофим Зайцев"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Адам Лукин"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Влад Назаров"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Игорь Лапин"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Горислав Юдин"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Лаврентий Щербаков"]));
$courier_controller->saveModel(Model\Courier::create(["full_name" => "Владилен Михеев"]));


$schedule_controller = new \Controller\Schedule($app);

$regions = $region_controller->findAll();
$couriers = $courier_controller->findAll();

foreach ($couriers as $courier)
{
	$start = 1;
	while ((time() - mktime(0, 0, 0, 7, $start, 2015)) > 0)
	{
		$region_key = array_rand($regions);
		$schedule_controller->saveModel(\Model\Schedule::create([
					"start" => date("Y-m-d H:i:s", mktime(0, 0, 0, 7, $start, 2015)),
					"end" => date("Y-m-d H:i:s", mktime(0, 0, 0, 7, $start + $regions[$region_key]["period"], 2015)),
					"region_id" => $regions[$region_key]["id"],
					"courier_id" => $courier["id"]
		]));
		
		$start += $regions[$region_key]["period"] + rand(0, 2);
	}
}
