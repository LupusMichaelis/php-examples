<?php

$create = 'create table if not exists vilains (firstname text, surname text)';

$con = new mysqli('localhost', null, null, 'test');
$con->query($create)
	or die($con->error);

function insert_vilain($firstname, $surname)
{
	global $con;
	$insert = "insert into vilains (firstname, surname) values ('$firstname', '$surname')";

	$con->query($insert)
		or die($con->error);
}

foreach
	(
		[ (object)
			[ 'firstname' => 'Freddy'
			, 'surname' => 'Krueger'
			]
		, (object)
			[ 'firstname' => 'Jason'
			, 'surname' => 'Todd'
			]
		] as $vilain
	)
	insert_vilain($vilain->firstname, $vilain->surname);
