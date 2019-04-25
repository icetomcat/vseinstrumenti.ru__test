<?php

namespace Model;

class Courier extends \Base\Model
{

	protected $full_name;

	public static function getSchema()
	{
		$schrma = new \Database\Schema("courier");
		
		$schrma->integer("id", null, "PRIMARY", true)	//
				->char("full_name");					// ФИО курьера

		return $schrma;
	}

	function getFull_name()
	{
		return $this->full_name;
	}

	function setFull_name($full_name)
	{
		$this->full_name = $full_name;
	}

}
