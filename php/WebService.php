<?php
/**
 * REST api functions implementation 
 *
 * @author Me
 * @version 0
 * @copyright Me, 01 May, 2011
 * @package streammanager
 **/

require_once("MPD.php");
require_once("MPDInstance.php");
require_once("Database.php");
require_once("Utils.php");

class WebService
{
	/**
	 * Add a new stream to the "streams" collection
	 * POST /api/streams
	 *
	 * @return void
	 * @author Me
	 **/
	public static function add($name, $url)
	{
		$nameValid = Utils::validateStreamName($name);
		$urlValid = Utils::validateURL($url);
		$data =	array();

		// Check for valid parameters
		if ($nameValid === true && is_array($urlValid))
		{
			$db = Database::connect();
			
			// Check for if name or URL alredy exist in the database
			$query = "SELECT * FROM streams WHERE name='". $name ."' OR url='". $url ."'";
			try
			{
				$result = $db->query($query);
			} catch(mysqli_mysql_exception $exception)
			{
				//if($execute->getCode() == 0)
				//{
				//}
			}
			
			// We've got a duplicate
			if($result->num_rows > 0)
			{
				$duplicateName = false;
				$duplicateURL = false;
				while($entry = $result->fetch_assoc())
				{
					if($entry['name'] == $name)
						$duplicateName = true;
					else if($entry['url'] == $url)
						$duplicateURL = true;
				}

				$data =
					array(
						"errors" => array(
										"error" => array()
									)
					);

				if($duplicateName)
					array_push($data['errors']['error'], array(
						"msg" => "duplicate",
						"parameter" => "name",
						"code" => DUPLICATE_NAME_ERROR
					));
				if($duplicateURL)
					array_push($data['errors']['error'], array(
						"msg" => "duplicate",
						"parameter" => "url",
						"code" => DUPLICATE_URL_ERROR
					));
				RestUtils::sendResponse(400, $data);

			}
				
			
			if ($statement = 
				$db->prepare("INSERT INTO streams (name, url, created)
								VALUES (?, ?, now())")) {
				$statement->bind_param("ss", $name, $url);
				$statement->execute();
			
				// If everything went ok throw a 200 with new stream info!
				$data =	array(
					"streams" => array(
						"stream" => array(
							"name" => $name,
							"url" => $url,
							"id" => $db->insert_id
						)
					)
				);

				RestUtils::sendResponse(200, $data);	
			}
		}
		else
		{
			$data =
				array(
					"errors" => array(
									"error" => array()
								)
				);

			if(is_string($nameValid))
				array_push($data['errors']['error'], array(
					"msg" => $nameValid,
					"parameter" => "name",
					"code" => INVALID_NAME_ERROR
				));
			if(is_string($urlValid))
				array_push($data['errors']['error'], array(
					"msg" => $urlValid,
					"parameter" => "url",
					"code" => INVALID_URL_ERROR
				));
			RestUtils::sendResponse(400, $data);
		}

		$db->close();
	}

	// PUT /api/streams/$id/play
	public static function play($id)
	{
		$mpd = MPDInstance::connect();
		$db = Database::connect();

		$result = 
			$db->query("SELECT * FROM streams WHERE id=$id");

		if($entry = $result->fetch_assoc())
		{
			if($mpd->connected)
			{
				$mpd->PLAdd($entry['url']);
				$count = $mpd->playlist_count;
				$mpd->Play($count - 1);	
				
				for($i = $count - 2; $i >= 0; $i--)
				{
					fb($i);
					$mpd->PLRemove($i);
				}

				if(!$mpd->repeat)
					$mpd->SetRepeat(1);

				RestUtils::sendResponse(
					200
				);
			}
			else
			{
				// Send 503 "Service unavailable"
				RestUtils::sendResponse(
					503
				);
			}
		}
		$db->close();
		$mpd->Disconnect();

	}

	// GET /api/streams
	public static function streams($data)
	{
		$db = Database::connect();

		$result =
			$db->query("SELECT * FROM streams ORDER BY name");
		if($result->num_rows > 0)
			$data =
				array(
					"streams" => array(
									"stream" => array()
								)
				);
						
		while($entry = $result->fetch_assoc())
		{
			array_push($data['streams']['stream'], array(
											"name" => $entry['name'],
											"url" => $entry['url'],
											"id" => $entry['id']
										));
		}	

		if(sizeof($data['streams']['stream']) > 0)
		{
			RestUtils::sendResponse(200, $data);
		}

		$db->close();
	}

	/**
	 * Remove a stream representation from database
	 * DELETE /api/streams/$id
	 *
	 * @return void
	 * @author Me
	 **/
	public static function remove($id)
	{
		if(!is_numeric($id))
			return NULL;

		$db = Database::connect();
		$result =
			$db->query("DELETE FROM streams WHERE id=". $id);

		// Check if stream existed and has been deleted from the database
		if($db->affected_rows == 1)
		{
			RestUtils::sendResponse(200);
		}
		else
		{
			RestUtils::sendResponse(400);
		}
	}

	/**
	 * Remove a stream representation from database
	 * GET /api/status
	 *
	 * @return void
	 * @author Me
	 **/
	public static function status($data)
	{
		$mpd = MPDInstance::connect();

		if ($mpd->connected) {
				
			$mpdInstance=$mpd->GetCurrentSong();

			// Check if the stream is in db
			$db = Database::connect();

			$result = 
				$db->query("SELECT * FROM streams WHERE url='". $mpdInstance['file'] ."'");

			if($row = $result->fetch_assoc())
			{
				fb($mpdInstance);
				RestUtils::sendResponse(200, 
					array(
						"stream" => array(
							"name" => $row['name'],
							"url" => $row['url'],
							"title" => $mpdInstance['Title'],
							"state" => $mpdInstance['state'],
							"coverUrl" => $row['cover_path']
						)
					)
				);
			}

			$db->close();
			$mpd->Disconnect();
		}	
	
	}
}

?>
