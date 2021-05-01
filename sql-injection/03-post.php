<?php

// WARNING: be advise this code is faulty and shouldn't be used but learning purpose
// It contains:
//
// * SQL injections
// * Header injection
// * is prone to DDOS
// * data disclosure
// * a business logic error
// * a control flow logic error
//

ini_set('html_errors', 'Off');
header('Content-type: application/json');
$create = 'create table if not exists vilains (firstname text, surname text)';

$con = new mysqli('db', null, null, 'test');
if(false === $con->query($create))
{
	$result = ['error' => $con->error];
	$result = json_encode($result);
}

function get_all_vilains()
{
	global $con;
	$select = 'select * from vilains';
	$result = $con->query($select);

	return false === $result ? $con->error : $result->fetch_all();
}

function get_vilains_by_firstname($firstname)
{
	global $con;
	$select = "select * from vilains where firstname = '$firstname'";
	$result = $con->query($select);

	return false === $result ? $con->error : $result->fetch_assoc();
}

function delete_vilains_by_firstname($firstname)
{
	global $con;
	$delete = "delete from vilains where firstname = '$firstname'";
	$result = $con->query($delete);

	return false === $result ? $con->error : true;
}

function insert_vilain($firstname, $surname)
{
	global $con;
	$insert = "insert into vilains (firstname, surname) values ('$firstname', '$surname')";

	return $con->query($insert) ? true : $con->error;
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
