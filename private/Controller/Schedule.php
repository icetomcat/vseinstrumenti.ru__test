<?php

namespace Controller;

class Schedule extends \Base\Controller
{

	public function getSchedule()
	{
		
	}

	public function collectSChedule($from, $to)
	{
		$schedule = $this->findAll([
			"where" => ["AND" => ["start(<=)" => $to, "end(>=)" => $from]],
			"join" => [
				"[>]" . Region::getTableName() . "(region)" => ["#schedule.region_id" => "region.id"],
				"[>]" . Courier::getTableName() . "(courier)" => ["#schedule.courier_id" => "courier.id"]
			],
			"columns" => ["schedule.*", "region.name(title)", "courier.full_name(courier)"]
				], ["schedule" => static::getTableName()]);
		foreach ($schedule as $key => &$value)
		{
			$schedule[$key]["status"] = strtotime($value["end"]) > time() ? "RUNNING" : "SUCCEEDED";
		}

		return $schedule;
	}

	public function methodGet_listJson()
	{
		$from = date_create_from_format("d.m.Y", $this->app->request()->get("from", date("d.m.Y", mktime(0, 0, 0, date('n') - 1))))->format("Y-m-d");
		$to = date_create_from_format("d.m.Y", $this->app->request()->get("to", date("d.m.Y", mktime(0, 0, 0, date('n')))))->format("Y-m-d");

		$this->app->response()->writeJson("schedule", $this->collectSChedule($from, $to));
		$this->app->response()->writeJson("from", $from);
		$this->app->response()->writeJson("to", $to);
	}

	public function methodGet_list()
	{
		$from = date_create_from_format("d.m.Y", $this->app->request()->get("from", date("d.m.Y", mktime(0, 0, 0, date('n') - 1))))->format("Y-m-d");
		$to = date_create_from_format("d.m.Y", $this->app->request()->get("to", date("d.m.Y", mktime(0, 0, 0, date('n')))))->format("Y-m-d");

		$schedule = $this->collectSChedule($from, $to);
		$couriers = (new Courier($this->app))->findAll(["order" => "full_name"]);
		$regions = (new Region($this->app))->findAll(["order" => "name"]);

		$this->app->render("schedule-list.twig", ["this" => $this, "schedule" => $schedule, "couriers" => $couriers, "regions" => $regions, "from" => date("d.m.Y", strtotime($from)), "to" => date("d.m.Y", strtotime($to))]);
	}

	public function methodGet_item($id)
	{

		$this->app->render("schedule-item.twig", ["this" => $this, "item" => $i]);
	}

	public function methodPost_listJson()
	{
		$regions = (new Region($this->app))->findAll();
		foreach ($this->app->request()->post("items", []) as $item)
		{
			$region = null;
			foreach ($regions as $region)
			{
				if ($region["id"] == $item["region_id"])
				{
					break;
				}
			}
			if ($region)
			{
				$item["start"] = date_create_from_format("d.m.Y", $item["start"])->format("Y-m-d");
				$item["end"] = date_add(date_create_from_format("Y-m-d", $item["start"]), date_interval_create_from_date_string($region["period"] . ' days'))->format("Y-m-d");
				$model = \Model\Schedule::create($item);
				$this->saveModel($model);
			}
		}
	}

	public function saveModel(\Base\Model $model)
	{
		$res = $this->findFirst(["where" => [ "AND" => [
					"OR" => [ "AND #start" => ["start(>=)" => $model->start, "start(<)" => $model->end], "AND #end" => ["end(>)" => $model->start, "end(<=)" => $model->end]],
					"courier_id" => $model->courier_id
				]]
		]);
		if ($res)
		{
			$this->app->halt(400, "Время уже занято");
		}
		return parent::saveModel($model);
	}

	public static function getModelClass()
	{
		return \Model\Schedule::class;
	}

	public static function getTableName()
	{
		return "schedule";
	}

}
