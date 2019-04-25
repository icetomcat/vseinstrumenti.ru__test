<?php

class App extends Slim\Slim
{

	/**
	 *
	 * @var Database\Adapter
	 */
	protected $database;
	protected $config;

	public function __construct(array $userSettings = array())
	{
		parent::__construct($userSettings);

		$this->container->singleton("response", function($c) {
			return new Base\Response();
		});
		$this->config = json_decode(file_get_contents(__DIR__ . "/config.json"), true);
		$this->database = new \Database\Adapter($this->config["database"]);
		$this->view(new \Slim\Views\Twig())->setTemplatesDirectory(__DIR__ . "/Templates");
		
		$this->mapRoute(["/(:controller(/)(:id))(/)", function($controller = "schedule", $id = null) {
				$class = "Controller\\" . ucfirst($controller);
				$method = ucfirst(strtolower($this->request()->getMethod()));
				if (class_exists($class))
				{
					$controller = new $class($this);
				}
				else
				{
					$this->notFound();
				}
				$postfix = "";
				if ($this->request->isAjax())
				{
					$postfix = "Json";
				}
				if (is_null($id))
				{
					$method = "method{$method}_list{$postfix}";
				}
				else
				{
					$method = "method{$method}_item{$postfix}";
				}
				if (method_exists($controller, $method))
				{
					$controller->$method($id);
				}
				else
				{
					$this->notFound();
				}
			}])->via(\Slim\Http\Request::METHOD_GET, \Slim\Http\Request::METHOD_HEAD, \Slim\Http\Request::METHOD_DELETE, \Slim\Http\Request::METHOD_OPTIONS, \Slim\Http\Request::METHOD_OVERRIDE, \Slim\Http\Request::METHOD_PATCH, \Slim\Http\Request::METHOD_POST, \Slim\Http\Request::METHOD_PUT);
	}

	public function db()
	{
		return $this->database;
	}

	public function run()
	{
		if ($this->request->isAjax())
		{
			$this->response->setJsonHeader();
			ob_start();
		}
		parent::run();
		if ($this->request->isAjax())
		{
			ob_end_clean();
			echo $this->response()->getJson();
		}
	}

}
