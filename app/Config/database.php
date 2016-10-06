<?php
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost', // MAY SHALL SACI-DB-HOST
		'login' => 'root',
		'password' => '@!Tr1ck0',
		'database' => 'saci',
		'prefix' => '',
		'encoding' => 'utf8',
	);

	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '@!Tr1ck0',
		'database' => 'saci',
		'prefix' => '',
		'encoding' => 'utf8',
	);
}
