<?php


class GearmanStatus
{

	protected $host;
	protected $port;


	public function __construct( $host = "127.0.0.1", $port = 4730 ) {
		if( $host ) $this->host = $host;
		if( $port ) $this->port = $port;
	}


	public function getStatus() {
		$status = null;

		$handle = @fsockopen($this->host, $this->port);

		if( $handle )
		{

			fwrite($handle, "status\n");
			while( !feof($handle) )
			{
				$line = fgets($handle, 4096);
				if( $line == ".\n" )
				{
					break;
				}
				if( preg_match("/^(?<function>.*)[ \t](?<queue>\d+)[ \t](?<running>\d+)[ \t](?<workersCount>\d+)/", $line, $matches) )
				{
					$function = $matches['function'];
					$status['status'][$function] = array(
						'server' => $this->host . ':' . $this->port,
						'function' => $function,
						'queue' => $matches['queue'],
						'running' => $matches['running'],
						'workersCount' => $matches['workersCount'],
					);

					unset($matches);
				}
			}

			fwrite($handle, "workers\n");
			while( !feof($handle) )
			{
				$line = fgets($handle, 4096);
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

					$status['workers'][$function][$fd] = array(
						'fd' => $fd,
						'ip' => $matches['ip'],
						'id' => $matches['id'],
					);

					unset($matches);
				}
			}
			fclose($handle);
		}

		if( ! $status) return FALSE;

		return (object)$status;
	}

}