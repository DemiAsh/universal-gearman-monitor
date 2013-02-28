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
		$status = null;

		if( $this->monitor )
		{
			$status['status'] = $this->getStatus();
			$status['workers'] = $this->getWorkers();
		}

		if( ! $status) return FALSE;

		return (object)$status;
	}

    protected function getStatus() {
		fwrite($this->monitor, "status\n");
		while( !feof($this->monitor) )
		{
			$line = fgets($this->monitor, 4096);
			if( $line == ".\n" )
			{
				break;
			}
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

		return $status;
    }

    protected function getWorkers() {
		fwrite($this->monitor, "workers\n");
		while( !feof($this->monitor) )
		{
			$line = fgets($this->monitor, 4096);
			if( $line == ".\n" )
			{
				break;
			}
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
		}

		return $status;
    }

}