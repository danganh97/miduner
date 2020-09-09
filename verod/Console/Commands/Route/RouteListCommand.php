<?php

namespace Midun\Console\Commands\Route;

use Midun\Console\Command;

class RouteListCommand extends Command
{
	/**
	 * Specific character
	 *
	 * @var string
	 */
	const SPECIFIC_LINE = '|';
	const SPECIFIC_UNDERSCORE = '_';
	const SPECIFIC_MIDDLESCORE = '-';
	const CLORSURE = 'Closure';
	const UNKNOWN = 'Unknown';
	const MATCH_ACTION = '@';

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'route:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Display list of registered routes';

	/**
	 * True format for command
	 *
	 * @var string
	 */
	protected $format = '>>hustle route:{type}';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Handle the command
	 *
	 * @return void
	 */
	public function handle()
	{
		$routes = app()->make('route')->collect();

		switch($this->getOptions('format')) {
			case 'array':
				print_r($routes);
				break;
			case 'json':
				$this->handleViewJsonFormat($routes);
				break;
			default:
				$space15 = $this->makeSpace(15);
				$space25 = $this->makeSpace(25);
				$space5 = $this->makeSpace(5);
				$this->output->printSuccessNoBackground($this->makeSpace(186, self::SPECIFIC_UNDERSCORE));
				$this->output->printSuccessNoBackground(self::SPECIFIC_LINE . "{$space15}uri{$space15}" . self::SPECIFIC_LINE . "{$space25}action{$space25}" . self::SPECIFIC_LINE . "{$space5}method{$space5}" . self::SPECIFIC_LINE . "{$space15}name{$space15}" . self::SPECIFIC_LINE . "{$space15}middlewares{$space15}" . self::SPECIFIC_LINE);
				$this->output->printSuccessNoBackground(self::SPECIFIC_LINE . $this->makeSpace(184, self::SPECIFIC_MIDDLESCORE) . self::SPECIFIC_LINE);
				foreach($routes as $route) {
					$uriLen = 33;
					$actionLen = 56;
					$methodLen = 16;
					$nameLen = 34;
					$middlewaresLen = 41;

					$route = (object)$route;

					if(!empty($route->getPrefix())) {
						$uri = \Midun\Routing\Routing::ROUTING_SEPARATOR . implode('/', $route->getPrefix()) . $route->getUri();
					} else {
						$uri = $route->getUri();
					}

					$uri = $uri . $this->makeSpace($uriLen - strlen($uri));

					$action = $route->getAction();

					switch(true) {
						case $action instanceof \Closure:
							$action = self::CLORSURE;
							break;
						case is_array($action):
							$action = array_shift($action) . self::MATCH_ACTION . array_shift($action);
							break;
						case is_string($action);
							break;
						default:
							$action = self::UNKNOWN;
							break;
					}
					$action = $action . $this->makeSpace($actionLen - strlen($action));
					$method = $route->getMethods() . $this->makeSpace($methodLen - strlen($route->getMethods()));
					$name = $route->getName() . $this->makeSpace($nameLen - strlen($route->getName()));
					$middlewares = is_array($route->getMiddlewares()) ? implode(', ', $route->getMiddlewares()) : $route->getMiddlewares();
					$middlewares = $middlewares . $this->makeSpace($middlewaresLen - strlen($middlewares));
					$this->output->printSuccessNoBackground(self::SPECIFIC_LINE . $uri . self::SPECIFIC_LINE . $action . self::SPECIFIC_LINE . $method . self::SPECIFIC_LINE . $name . self::SPECIFIC_LINE . $middlewares . self::SPECIFIC_LINE);
				}
				$this->output->printSuccessNoBackground($this->makeSpace(186, self::SPECIFIC_MIDDLESCORE));
		}
	}

	/**
	 * Handle view json format
	 *
	 * @param array $routes
	 *
	 * @return void
	 */
	private function handleViewJsonFormat(array $routes)
	{
		foreach($routes as $route) {
			echo str_replace("\\", "", json_encode(['uri' => $route->getUri(), 'action' => $route->getAction(), 'method' => $route->getMethods(), 'name' => $route->getName(), 'middlewares' => $route->getMiddlewares()])) . PHP_EOL;
		}
	}

	/**
	 * Make space
	 *
	 * @param int $max
	 * @param string $specific
	 *
	 * @return string
	 */
	public function makeSpace(int $max, string $specific = ' ')
	{
		$space = '';

		for($i = 1; $i <= $max; $i++) {
			$space .= $specific;
		}

		return $space;
	}
}
