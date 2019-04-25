<?php

namespace Controller;

class Region extends \Base\Controller
{

	public static function getModelClass()
	{
		return \Model\Region::class;
	}

	public static function getTableName()
	{
		return "region";
	}

}
