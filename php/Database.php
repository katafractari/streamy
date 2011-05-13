<?php

class Database 
{
	public static function connect()
	{
		$db = new mysqli("localhost", "root", "", "streammanager");
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}

		return $db;
	}
}
