<?php

require("lastfmapi/lastfmapi.php");

class Lastfm
{
	// Properties
	private static $apiKey = "29fcca8e98b5993c9b3bd0066eaa3d30";
	private $apiClass;

	// Init Lastfm REST client
	public function __construct()
	{
		$authVars['apiKey'] = $this->apiKey;
		$auth = new lastfmApiAuth('setsession', $this->authVars);
		$apiClass = new lastfmApi();
	}

	// Get a top hit picture url for a given $title sent by a stream
	public static function getPicture($title)
	{
		// TODO
		$methodVars = array(
			'artist' => 'Athlete',
			'page' => 2,
			'limit' => 10
		);
		
		$artistClass = $apiClass->getPackage($auth, 'artist', $config);

	}
		
}


?>
