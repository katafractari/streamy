<?php

class MPDInstance
{
	/**
	 * Check which output is the selected one, connect to it and return and 
	 * instance of mpd class
	 *
	 * @return object::mpd or NULL
	 * @author Me
	 **/
	public static function connect(&$db)
	{
		$ret = NULL;

		$selectedOutput = $db->getCurrentOutput();
		if(strlen($selectedOutput['hostname']) > 0 &&
			strlen($selectedOutput['port']) > 0)
		{
			$ret = new mpd(
				$selectedOutput['hostname'],
				$selectedOutput['port']
			);
		}

		return $ret;
	}
}

?>
