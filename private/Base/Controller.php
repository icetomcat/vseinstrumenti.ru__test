<?php

namespace Base;

abstract class Controller implements \Interfaces\IController
{

	/**
	 *
	 * @var \App
	 */
	protected $app = null;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function app()
	{
		return $this->app;
	}

	public function createModel(array $properties = [])
	{
		$model_class = $this->getModelClass();
		return new $model_class($properties);
	}

	public function saveModel(Model $model)
	{
		try
		{
			if(($v = $model->validate()) !== true)
			{
				$this->app->halt(400, reset(reset($v)));
			}
			$this->app->db()->startTransaction();
			$model->id = $this->app->db()->insert(["table" => $this->getTableName(), "data" => $model->__toArray()])->execute();
			$this->app->db()->commit();
		}
		catch (\Exception $exc)
		{
			$this->app->db()->rollBack();
			throw $exc;
		}
	}

	public function findAll(array $query = [], $table = null)
	{
		if (!is_null($table))
		{
			$query["table"] = $table;
		}
		else
		{
			$query["table"] = $this->getTableName();
		}
		return $this->app->db()->select($query)->fetchAll();
	}

	public function findById($id)
	{
		return $this->findFirst("WHERE `id`=:id", [":id" => $id]);
	}

	public function findFirst(array $query = [], $table = null)
	{
		if (!is_null($table))
		{
			$query["table"] = $table;
		}
		else
		{
			$query["table"] = $this->getTableName();
		}
		$query["limit"] = 1;
		$row = $this->app->db()->select($query)->fetch();

		$model_class = $this->getModelClass();
		if ($row)
		{
			return new $model_class($row);
		}
		else
		{
			return null;
		}
	}

	public function deleteAll($query)
	{
		$query["table"] = $this->getTableName();
		$this->app->db()->delete($query)->execute();
	}

}
