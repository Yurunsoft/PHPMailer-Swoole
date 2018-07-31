<?php
namespace PHPMailer\PHPMailer;

use Yurun\Util\Swoole\PHPMailer\ResourceMap;
use Yurun\Util\Swoole\PHPMailer\SwooleClientError;

function fsockopen($host, $port, &$errno, &$errstr, $timeout)
{
	$url = parse_url($host);
	$sockType = SWOOLE_SOCK_TCP;
	if('ssl' === $url['scheme'])
	{
		$sockType = $sockType | SWOOLE_SSL;
	}
	$host = $url['host'];
	return newCoClient($sockType, $host, $port, $timeout);
}

function stream_socket_client($address, &$errno, &$errstr, $timeout, $a, $b)
{
	$url = parse_url($address);
	$sockType = SWOOLE_SOCK_TCP;
	if('ssl' === $url['scheme'])
	{
		$sockType = $sockType | SWOOLE_SSL;
	}
	$host = $url['host'];
	$port = $url['port'];
	return newCoClient($sockType, $host, $port, $timeout);
}

function newCoClient($sockType, $host, $port, $timeout)
{
	$client = new \Swoole\Coroutine\Client($sockType);
	$result = $client->connect($host, $port, $timeout);
	if($result)
	{
		$resource = fopen('php://memory', 'w+');
		ResourceMap::addResource($resource, $client);
		return $resource;
	}
	else
	{
		$errno = $client->errCode;
		$errstr = SwooleClientError::getErrorString($errno);
		return false;
	}
}

function stream_get_meta_data($resource)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \stream_get_meta_data($resource);
	}
	return [
		'timed_out'	=>	true,
		'eof'		=>	feof($resource),
	];
}

function stream_set_timeout($resource, $timeout)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \stream_set_timeout($resource, $timeout);
	}
}

function stream_socket_enable_crypto($resource, $bool, $method)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \stream_socket_enable_crypto($resource, $bool, $method);
	}

	$client->set([
		'ssl_method'	=>	SWOOLE_TLSv1_2_CLIENT_METHOD,
	]);

	return $client->enableSSL();
}

function fclose($resource)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \fclose($resource);
	}
	ResourceMap::releaseResource($resource);
	return $client->close();
}

function fwrite($resource, $data, $length = null)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \fwrite($resource, $data, $length);
	}
	if(null === $length)
	{
		$length = strlen($data);
	}
	else
	{
		$data = substr(0, $length);
	}
	return $client->send($data);
}

function feof($resource)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \feof($resource);
	}
	return false;
}

function fgets($resource, $length = null)
{
	$client = ResourceMap::getObject($resource);
	if(null === $client)
	{
		return \fgets($resource, $length);
	}

	$data = $client->recv();
	
	return $data;
}

function stream_select(...$args)
{
	return true;
}