<?php

namespace Controller;

class Courier extends \Base\Controller
{

	public static function getModelClass()
	{
		return \Model\Courier::class;
	}

	public static function getTableName()
	{
		return "courier";
	}

}
