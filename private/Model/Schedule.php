<?php

namespace Model;

use Base\Model;
use Database\Schema;
use rock\validate\Validate;

class Schedule extends Model
{

	protected $courier_id;
	protected $region_id;
	protected $start;
	protected $end;

	public static function getSchema()
	{
		$schrma = new Schema("schedule");

		$schrma->integer("id", null, "PRIMARY", true) //
				->integer("courier_id")  //
				->integer("region_id")  //
				->date("start") // Начало и
				->date("end"); // конец(расчетный) поездки курьера в регион

		return $schrma;
	}

	function getCourier_id()
	{
		return $this->courier_id;
	}

	function getRegion_id()
	{
		return $this->region_id;
	}

	function getStart()
	{
		return $this->start;
	}

	function getEnd()
	{
		return $this->end;
	}

	function setCourier_id($courier_id)
	{
		$this->courier_id = $courier_id;
	}

	function setRegion_id($region_id)
	{
		$this->region_id = $region_id;
	}

	function setStart($start)
	{
		$this->start = $start;
	}

	function setEnd($end)
	{
		$this->end = $end;
	}

	/**
	 * 
	 * @return bool | array
	 */
	public function validate()
	{
		$v = Validate::attributes([
					"courier_id" => Validate::required()->numeric(),
					"region_id" => Validate::required()->numeric(),
					"start" => Validate::required()->date(),
					"end" => Validate::required()->date()
		]);

		$res = $v->validate($this->__toArray());
		return $res === true ? true : $v->getErrors();
	}

}
