<?php
    $username = "root";
	$password = null;
	$host = "localhost";
	$dbname = "gym";
	$db = NULL;
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

	try
	{
		DB::$dsn = "mysql:host=$host;dbname=$dbname";
		DB::$account = $username;
		DB::$password = $password;
		$db = DB::instance();
	}
	catch(PDOException $ex)
	{
	    die("Failed to connect to the database: " . $ex->getMessage());
	}


	$conn = new mysqli($host, $username, $password, $dbname);
