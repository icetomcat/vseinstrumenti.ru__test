<?php

namespace Base;

class Response extends \Slim\Http\Response
{

	protected $json;

	public function setJsonHeader()
	{
		$this->headers["Content-Type"] = "application/json;charset=UTF-8";
	}

	public function writeJson($key, $value)
	{
		if (isset($this->json[$key]))
		{
			if (is_scalar($this->json[$key]))
			{
				$this->json[$key] = [$this->json[$key], $value];
			}
			else
			{
				$this->json[$key][] = $value;
			}
		}
		else
		{
			$this->json[$key] = $value;
		}
	}

	public function getJson()
	{
		if($this->body)
		{
			$this->json["body"] = $this->body;
		}
		return json_encode($this->json);
	}
}