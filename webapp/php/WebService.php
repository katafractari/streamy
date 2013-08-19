<?php

/**
 * REST api functions implementation 
 *
 * @author Me
 * @version 0
 * @copyright Me, 01 May, 2011
 * @package streammanager
 * */
require_once("MPD.php");
require_once("MPDInstance.php");
require_once("Database.php");
require_once("Utils.php");

class WebService {

    /**
     * Add a new stream to the "streams" collection
     * POST /api/streams
     *
     * @return void
     * @author Me
     * */
    public static function add($name, $url) {
	$nameValid = Utils::validateStreamName($name);
	$urlValid = Utils::validateURL($url);

	$data = array();

	// Check for valid parameters
	if ($nameValid === true && is_array($urlValid)) {
	    $db = new Database();

	    // Check for if name or URL alredy exist in the database
	    if (($duplicateStreams = $db->streamExists($name, $url)) != NULL) {
		$duplicateName = false;
		$duplicateURL = false;
		for ($i = 0; $i < $sizeof($duplicateStreams); $i++) {
		    if ($duplicateStreams[$i]['name'] == $name)
			$duplicateName = true;
		    else if ($duplicateStreams[$i]['url'] == $url)
			$duplicateURL = true;
		}

		$data =
			array(
			    "errors" => array(
				"error" => array()
			    )
		);

		if ($duplicateName)
		    array_push($data['errors']['error'], array(
			"msg" => "duplicate",
			"parameter" => "name",
			"code" => DUPLICATE_NAME_ERROR
		    ));
		if ($duplicateURL)
		    array_push($data['errors']['error'], array(
			"msg" => "duplicate",
			"parameter" => "url",
			"code" => DUPLICATE_URL_ERROR
		    ));
		RestUtils::sendResponse(400, $data);
	    }


	    if (($id = $db->addStream($name, $url)) != NULL) {
		// If everything went ok throw a 200 with new stream info!
		$data = array(
		    "streams" => array(
			"stream" => array(
			    "name" => $name,
			    "url" => $url,
			    "id" => $id
			)
		    )
		);

		RestUtils::sendResponse(200, $data);
	    }
	} else {
	    $data =
		    array(
			"errors" => array(
			    "error" => array()
			)
	    );

	    if (is_string($nameValid))
		array_push($data['errors']['error'], array(
		    "msg" => $nameValid,
		    "parameter" => "name",
		    "code" => INVALID_NAME_ERROR
		));
	    if (is_string($urlValid))
		array_push($data['errors']['error'], array(
		    "msg" => $urlValid,
		    "parameter" => "url",
		    "code" => INVALID_URL_ERROR
		));
	    RestUtils::sendResponse(400, $data);
	}

	$db->close();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author Me
     * */
    // POST /api/streams/play
    public static function pauseCurrent() {
	$db = new Database();
	$mpd = MPDInstance::connect($db);

	if ($mpd->connected) {
	    $mpd->Stop();
	}
	$mpd->Disconnect();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author Me
     * */
    // POST /api/streams/play
    public static function playCurrent() {
	$db = new Database();
	$mpd = MPDInstance::connect($db);

	if ($mpd->connected) {
	    $mpd->Play();
	}
	$mpd->Disconnect();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author Me
     * */
    // PUT /api/streams/$id/play
    public static function play($id) {
	$db = new Database();
	$mpd = MPDInstance::connect($db);

	if (($stream = $db->getStream($id)) != NULL) {
	    if ($mpd->connected) {
		$mpd->PLAdd($stream['url']);
		$count = $mpd->playlist_count;
		$mpd->Play($count - 1);

		// Remove all but the played item!
		for ($i = $count - 2; $i >= 0; $i--) {
		    $mpd->PLRemove($i);
		}

		if (!$mpd->repeat)
		    $mpd->SetRepeat(1);

		RestUtils::sendResponse(
			200
		);
	    }
	    else {
		// Send 503 "Service unavailable"
		RestUtils::sendResponse(
			400
		);
	    }
	} else {
	    // Send 503 "Service unavailable"
	    RestUtils::sendResponse(
		    400
	    );
	}
	$db->close();
	$mpd->Disconnect();
    }

    // GET /api/streams
    public static function streams($data) {
	$db = new Database();

	if (($streams = $db->getStreams()) != NULL) {
	    $data =
		    array(
			"streams" => array(
			    "stream" => array()
			)
	    );

	    for ($i = 0; $i < sizeof($streams); $i++) {
		array_push($data['streams']['stream'], array(
		    "name" => $streams[$i]['name'],
		    "url" => $streams[$i]['url'],
		    "id" => $streams[$i]['id']
		));
	    }

	    if (sizeof($data['streams']['stream']) > 0) {
		RestUtils::sendResponse(200, $data);
	    }
	} else {
	    RestUtils::sendResponse(400, $data);
	}

	$db->close();
    }

    /**
     * Remove a stream representation from database
     * DELETE /api/streams/$id
     *
     * @return void
     * @author Me
     * */
    public static function remove($id) {
	if (!is_numeric($id))
	    return NULL;

	$db = new Database();
	if ($db->removeStream($id)) {
	    RestUtils::sendResponse(200);
	} else {
	    RestUtils::sendResponse(400);
	}
    }

    /**
     * Remove a stream representation from database
     * GET /api/status
     *
     * @return void
     * @author Me
     * */
    public static function status($data) {
	$db = new Database();
	$mpd = MPDInstance::connect($db);
//		var_dump($mpd);

	if ($mpd->connected) {

	    $mpdInstance = $mpd->GetCurrentSong();

	    // Check if the stream is in db
	    if (($stream = $db->streamExists(NULL, $mpdInstance['file'])) != NULL) {
		RestUtils::sendResponse(200, array(
		    "stream" => array(
			"name" => $stream[0]['name'],
			"url" => $stream[0]['url'],
			"state" => $mpd->state,
			"coverUrl" => $stream[0]['cover_path']
		    )
			)
		);
	    } else {
		RestUtils::sendResponse(400);
	    }

	    $mpd->Disconnect();
	} else {
	    RestUtils::sendResponse(400);
	}
	$db->close();
    }

    /**
     * Get all outputs
     *
     * @return void
     * @author Me
     * */
    public static function getOutputs() {
	$data = NULL;
	$db = new Database();

	if (($outputs = $db->getOutputs()) != NULL) {
	    $data =
		    array(
			"outputs" => array(
			    "output" => array()
			)
	    );

	    for ($i = 0; $i < sizeof($outputs); $i++) {
		array_push($data['outputs']['output'], array(
		    "name" => $outputs[$i]['name'],
		    "id" => $outputs[$i]['id'],
		    "selected" => $outputs[$i]['selected']
		));
	    }
	    RestUtils::sendResponse(200, $data);
	} else {
	    RestUtils::sendResponse(400);
	}
    }

    /**
     * Set output to output with id $id
     * PUT /api/streams/outputs/$id
     *
     * @return void
     * @author Me
     * */
    public static function setOutput($id) {
	if (!is_numeric($id))
	    return NULL;

	$db = new Database();
	if ($db->setOutput($id) == 1) {
	    RestUtils::sendResponse(200);
	} else {
	    RestUtils::sendResponse(400);
	}
    }

    /**
     * Add an output
     * POST /api/streams/outputs
     *
     * @return void
     * @author Me
     * */
    public static function addOutput($name, $hostname, $port) {
	$nameValid = Utils::validateOutputName($name);
	$hostnameValid = Utils::validateOutputHostname($hostname);

	$db = new Database();
	if (is_numeric($id = $db->addOutput($name, $hostname, $port))) {
	    RestUtils::sendResponse(200);
	} else {
	    RestUtils::sendResponse(400);
	}
    }

    /**
     * Get current title from stream $id
     * GET /api/streams/$id/title
     *
     * @return string with title or NULL
     * @author Me
     * */
    public static function getStreamTitle($id) {
	if (!is_numeric($id))
	    return NULL;

	$db = new Database();
	if (($stream = $db->getStream($id)) != NULL) {
	    //print $stream['url']."\n";
	    $title = Utils::getStreamTitle($stream['url']);
	    if ($title != NULL) {
		$data =
			array(
			    "stream" => array(
				"title" => $title
			    )
		);
		RestUtils::sendResponse(200, $data);
	    } else {
		RestUtils::sendResponse(400, $data);
	    }
	}
    }

}

?>
