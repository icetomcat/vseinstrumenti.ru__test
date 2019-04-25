<?php

namespace Base;

abstract class Model implements \ArrayAccess
{

	protected $id = null;

	public function __construct(array $properties = [])
	{
		foreach ($properties as $key => $value)
		{
			$this->__set($key, $value);
		}
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
	
	static public function create(array $properties = [])
	{
		return new static($properties);
	}

	public function __set($name, $value)
	{
		$mutator = "set" . ucfirst(strtolower($name));
		if (method_exists($this, $mutator))
		{
			$this->$mutator($value);
		}
		else
		{
			throw new \Exception("Bla bla bla");
		}
	}

	public function __get($name)
	{
		
		$accessor = "get" . ucfirst(strtolower($name));
		if (method_exists($this, $accessor))
		{
			
			return $this->$accessor();
		}
		else
		{
			throw new \Exception("Fuck $name");
		}
	}
	
	public function offsetExists($offset)
	{
		return property_exists($this, $offset);
	}

	public function offsetGet($offset)
	{
		return $this->__get($offset);
	}

	public function offsetSet($offset, $value)
	{
		$this->__set($offset, $value);
	}

	public function offsetUnset($offset)
	{
		$this->__set($offset, null);
	}
	
	public function __toArray()
	{
		return get_object_vars($this);
	}

	public function __toString()
	{
		return print_r($this->__toArray(), true);
	}
	
	public function validate()
	{
		return true;
	}
}