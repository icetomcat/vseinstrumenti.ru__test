<?php

namespace Model;

class Region extends \Base\Model
{

	protected $name;
	protected $period;

	public static function getSchema()
	{
		$schrma = new \Database\Schema("region");

		$schrma->integer("id", null, "PRIMARY", true)	//
				->char("name")->unique()				// Наименование региона
				->integer("period");					// Время доставки в регион от Москвы в днях

		return $schrma;
	}

	function getName()
	{
		return $this->name;
	}

	function getPeriod()
	{
		return $this->period;
	}

	function setName($name)
	{
		$this->name = $name;
	}

	function setPeriod($period)
	{
		$this->period = $period;
	}

}
