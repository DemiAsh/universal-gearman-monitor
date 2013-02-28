<?php


class GearmanStatus
{

	protected $host;
	protected $port;
	protected $monitor;

	public function __construct( $host = "127.0.0.1", $port = 4730 ) {
		if( $host ) $this->host = $host;
		if( $port ) $this->port = $port;

		$this->monitor = @fsockopen($this->host, $this->port);
	}

	public function __destruct() {
		if($this->monitor) fclose($this->monitor);
	}


	public function status() {
		if( ! $this->monitor) return FALSE;

		$status = null;

		if( $this->monitor )
		{
			$status['status'] = $this->getStatus();
			$status['workers'] = $this->getWorkers();
		}

		return (object)$status;
	}

	public function shutdown($immedatly = FALSE) {
		
	}

	protected function getResponse() {
		$response = null;

		while (true)
		{
			$data = fgets($this->monitor, 4096);

			if ($data == ".\n")
			{
				break;
			}
			$response .= $data;
		}
		
		return $response;
	}

	protected function sendCmd($cmd) {
		fwrite($this->monitor, $cmd . "\n");
	}

	protected function getStatus() {
		$this->sendCmd('status');

		$line = $this->getResponse();

		if( preg_match("/^(?<function>.*)[ \t](?<queue>\d+)[ \t](?<running>\d+)[ \t](?<workersCount>\d+)/", $line, $matches) )
		{
			$function = $matches['function'];
			$status[$function] = array(
				'server' => $this->host . ':' . $this->port,
				'function' => $function,
				'queue' => $matches['queue'],
				'running' => $matches['running'],
				'workersCount' => $matches['workersCount'],
			);
		
			unset($matches);
		}

		return $status;
	}

	protected function getWorkers() {
		$this->sendCmd('workers');

		$line = $this->getResponse();

		if( preg_match("/^(?<fd>\d+)[ \t](?<ip>.*?)[ \t](?<id>.*?) : ?(?<function>.*)/", $line, $matches) )
		{
			$function = $matches['function'];
			$fd = $matches['fd'];

			if( !$function )
				$function = 'monitor';

			$status[$function][$fd] = array(
				'fd' => $fd,
				'ip' => $matches['ip'],
				'id' => $matches['id'],
			);

			unset($matches);
		}

		return $status;
	}

}