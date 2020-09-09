<?php

namespace Midun\Console\Commands\Exec;

use Midun\Console\Command;

class ExecQueryCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'exec:query';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run query live';

	/**
	 * Option required
	 *
	 * @var array
	 */
	protected $required = ['query'];

	/**
	 * True format for command
	 *
	 * @var string
	 */
	protected $format = '>> midun exec:query --query="{select sql}"';

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
		$query = $this->getOptions('query');

		try {
			$connection = app('connection')->getConnection();

			$start = microtime(true);

			if(explode(' ', $query)[0] != 'select') {
				$this->output->printError('Only execute SELECT SQL');
				exit(1);
			}

			$statement = $connection->query($query);

			$touched = $statement->rowCount();

			$result = $this->getOptions('test') == 'true' ? '' : json_encode($statement->fetchAll());

			$end = microtime(true);

			$execution_time = (float)($end - $start);

			$execution_time = number_format((float)$execution_time, 10);

			$this->output->print($result);

			$this->output->print("Ran query: $query");
			$this->output->print("Ran time: " . $execution_time . ' seconds');
			$this->output->print("Touched object: $touched");
		} catch(\PDOException $e) {
			$this->output->printError($e->getMessage());
		}
	}
}
