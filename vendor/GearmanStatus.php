<?php


class GearmanStatus
{

	public $up = FALSE;
	protected $host;
	protected $port;
	protected $monitor;

	public static function factory( $host = "127.0.0.1", $port = 4730 ) {
		return new self($host,$port);
	}

	public function __construct( $host, $port ) {
		if( $host ) $this->host = $host;
		if( $port ) $this->port = $port;

		$this->monitor = @fsockopen($this->host, $this->port);
		if ($this->monitor) $this->up = TRUE;
	}

	public function __destruct() {
		$this->_disconnect();
	}

	public function status() {
		if( ! $this->monitor)
		{
			return FALSE;
		}
		else
		{
			$status = array();
			$status['status'] = $this->getStatus();
			$status['workers'] = $this->getWorkers();

			return (object)$status;
		}
	}

	public function setMaxQueue($function, $maxSize) {
		// TODO: Validation
		$this->sendCmd("maxqueue {$function} {$maxSize}", FALSE);
	}

	public function shutdown($gently = FALSE) {
		$cmd = ($gently ? "shutdown graceful" : "shutdown");
		$this->sendCmd($cmd, FALSE);
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

	protected function sendCmd($cmd,$response = TRUE) {
		if ($this->monitor)
		{
			fwrite($this->monitor, $cmd . "\n");
			if( $response ) return trim($this->getResponse());
		}
	}

	public function getStatus() {
		$status = array();
		$lines = $this->sendCmd('status');

		if ($lines)
		{
			$lines = explode("\n", $lines);
			foreach($lines as $line)
			{
				$matches = array();
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
			}
			asort($status);
			return $status;
		}
	}

	public function getWorkers() {
		$status = array();
		$lines = $this->sendCmd('workers');

		if ($lines)
		{
			$lines = explode("\n", $lines);
			foreach($lines as $line)
			{
				$matches = array();
				if( preg_match("/^(?<fd>\d+)[ \t](?<ip>.*?)[ \t](?<id>.*?) : ?(?<function>.*)/", $line, $matches) )
				{
					$function = $matches['function'];
					$fd = $matches['fd'];

					if( !$function ) $function = 'monitor';

					$status[$function][$fd] = array(
						'fd' => $fd,
						'ip' => $matches['ip'],
						'id' => $matches['id'],
					);

					unset($matches);
				}
			}
			asort($status);
			return $status;
		}
	}

	private function _disconnect() {
		if($this->monitor) fclose($this->monitor);
	}

}
