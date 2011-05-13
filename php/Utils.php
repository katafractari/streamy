<?php
/**
 * Utility class
 *
 * @author Me
 * @version 0
 * @copyright Me, 01 May, 2011
 * @package streammanager
 **/

class Utils
{
	/**
	 * Include a template from separate file
	 *
	 * @return void
	 * @author Me
	 **/
	public static function includeTemplate($templateName)
	{
		if(strlen($templateName) > 0)
			include("templates/$templateName.php");
	}

	/**
	 * Extract host + port
	 *
	 * @return void
	 * @author Me
	 **/
	public static function parseURL($url)
	{
	}
	
	/**
	 * Validate stream URL
	 *
	 * @return string with error description or array with match
	 * @author Me
	 **/
	public static function validateURL($url)
	{
		$rt =
			preg_match(
				'/^http[s]?:\/\/([a-z0-9-.]+)(:[0-9]+)?.*$/i',
				$url,
				$matches
			);
		if($rt > 0)
		{
			$data = array();
			if(sizeof($matches) >= 2)
			{
				$data = array(
					"url" => array(
						"host" => $matches[1]
					)
				);
				if (sizeof($matches) == 3) {
					$data['url']['port'] = substr($matches[2], 1);
				}
									
				return $data;
			}
		}
		return "Invalid URL.";
	}
	
	/**
	 * Validate a stream Name (32 chars max)
	 *
	 * @return void
	 * @author Me
	 **/
	public static function validateStreamName($name)
	{
		return (strlen($name) < 32 && strlen($name) > 0) ? true : "Name must be between 1 to 32 characters";
	}
}
?>
