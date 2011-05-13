<?php

class MPDInstance
{
	public static function connect()
	{
		return new mpd("10.2.2.254", 6611);
	}

}

?>
