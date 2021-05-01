<?php

////////////////////////////////////////////////////////////////////////////////
//
// WARNING: be advise not to use this code in production
//			This code is faulty and shouldn't be used but learning purpose.
//
//			It contains:
//
//				* SQL injections
//				* XSS
// 				* Header injection
// 				* is prone to DDOS
// 				* data disclosure
// 				* many business logic errors
// 				* many control flow logic errors
//
////////////////////////////////////////////////////////////////////////////////

ini_set('html_errors', 'Off');
header('Content-type: application/json');
$create = 'create table if not exists vilains (firstname text, surname text)';

$con = new mysqli('db', null, null, 'test');
if(false === query($create))
{
	$result = ['error' => $con->error];
	$result = json_encode($result);
}

$result = [];

if(isset($_POST['firstname']) && isset($_POST['surname']))
{
	extract($_POST);
	$result = insert_vilain($firstname, $surname);
	if(true === $result)
	{
		header('HTTP/1.1 201 Created');
		header('Location: ' . $_SERVER['PHP_SELF'] . "?firstname=$firstname");
		die();
	}
	else
	{
		header('HTTP/1.1 500 Internal Server Error');
		die(json_encode($result));
	}
}
elseif(isset($_GET['firstname']))
{
	extract($_GET);

	if('DELETE' === $_SERVER['REQUEST_METHOD'])
	{
		$result = delete_vilains_by_firstname($firstname);
		if(true === $result)
		{
			header('HTTP/1.1 204 No Content');
			die();
		}
		elseif(empty($result))
		{
			header('HTTP/1.1 404 Not Found');
			die('{"firstname": "'. $firstname . '"}');
		}
		else
		{
			header('HTTP/1.1 500 Internal Server Error');
			die(json_encode($result));
		}
	}
	else
	{
		$result[$firstname] = get_vilains_by_firstname($firstname);
		$result = json_encode($result);
		empty($result[$firstname])
			and header('HTTP/1.1 404 Not Found');
		header('Content-Length: ' . strlen($result));
		die($result);
	}
}
else
{
	$result['all'] = get_all_vilains();
	$result = json_encode($result);
	header('Content-Length: ' . strlen($result));
	die($result);
}

$result = json_encode($result);
if(false === $result)
	$result = '{ "error": "' . json_last_error() . '"}';

header('Content-Length: ' . strlen($result));
die($result);

function query($fmt, ...$args)
{
	global $con;
	$args = array_map([$con, 'real_escape_string'], $args);
	$query = vsprintf($fmt, $args);
	return $con->query($query);
}

function get_all_vilains()
{
	$select = 'select * from vilains';
	$result = query($select);

	return false === $result ? $con->error : $result->fetch_all();
}

function get_vilains_by_firstname($firstname)
{
	global $con;
	$select = "select * from vilains where firstname = '%s'";
	$result = query($select, $firstname);

	return false === $result ? $con->error : $result->fetch_assoc();
}

function delete_vilains_by_firstname($firstname)
{
	global $con;
	$delete = "delete from vilains where firstname = '%s'";
	$result = query($delete, $firstname);

	return false === $result ? $con->error : true;
}

function insert_vilain($firstname, $surname)
{
	global $con;
	$insert = "insert into vilains (firstname, surname) values ('$firstname', '$surname')";

	return query($insert, $firstname, $surname) ? true : $con->error;
}
