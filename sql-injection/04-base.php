<?php

$create = 'create table if not exists vilains (firstname text, surname text)';

$firstname = 'Freddy';
$surname = 'Krueger';
$insert_fmt = "insert into vilains (firstname, surname) values ('%s', '%s')";

$con = new mysqli('localhost', null, null, 'test');

$con->query($create)
	or die($con->error);

$insert = sprintf
	( $insert_fmt
	, $con->real_escape_string($firstname)
	, $con->real_escape_string($surname)
	);

$con->query($insert)
	or die($con->error);
