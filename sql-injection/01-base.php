<?php

$create = 'create table if not exists vilains (firstname text, surname text)';

$firstname = 'Freddy';
$surname = 'Krueger';
$insert = "insert into vilains (firstname, surname) values ('$firstname', '$surname')";

$con = new mysqli('localhost', null, null, 'test');

$con->query($create)
	or die($con->error);

$con->query($insert)
	or die($con->error);
