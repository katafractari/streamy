<?php

/**
 * REST api router
 *
 * @author Me
 * @version 0
 * @copyright Me, 01 May, 2011
 * @package streammanager
 * */
require_once("php/Defines.php");
require_once("php/FirePHPCore/fb.php");
require_once("php/RestUtils.php");
require_once("php/WebService.php");
require_once("php/RestRequest.php");

$data = RestUtils::processRequest();

switch ($data->getMethod()) {
    // GET functions
    case 'get':
	if (preg_match("/status$/", $data->getURI(), $matches)) {
	    WebService::status($data);
	} else if (preg_match("/streams$/", $data->getURI(), $matches)) {
	    WebService::streams($data);
	} else if (preg_match("/outputs$/", $data->getURI(), $matches)) {
	    WebService::getOutputs($data);
	} else if (preg_match("/streams\/(\d+)\/title$/", $data->getURI(), $matches)) {
	    WebService::getStreamTitle($matches[1]);
	}
	break;
    // PUT functions
    case 'put':
	if (preg_match("/\/(\d+)\/play$/", $data->getURI(), $matches)) {
	    WebService::play($matches[1]);
	}
	if (preg_match("/\/outputs\/(\d+)$/", $data->getURI(), $matches)) {
	    WebService::setOutput($matches[1]);
	}
	break;
    // POST functions
    case 'post':
	//Check uri
	if (preg_match("/streams$/", $data->getURI(), $matches)) {
	    // Check for presence of request variables
	    $requestVars = $data->getRequestVars();
	    $url = trim($requestVars['url']);
	    $name = trim($requestVars['name']);
	    if (strlen($name) > 0 && strlen($url) > 0) {
		// Pass POST vars to "add" function
		WebService::add(
			$requestVars['name'], $requestVars['url']
		);
	    }
	    // Request params requirements not matched, send error response
	    else {
		RestUtils::sendResponse(400);
	    }
	} else if (preg_match("/play$/", $data->getURI())) {
	    WebService::playCurrent();
	    break;
	} else if (preg_match("/pause$/", $data->getURI())) {
	    WebService::pauseCurrent();
	    break;
	} else if (preg_match("/output$/", $data->getURI(), $matches)) {
	    $requestVars = $data->getRequestVars();
	    $name = trim($requestVars['name']);
	    $hostname = trim($requestVars['hostname']);
	    $port = trim($requestVars['port']);

	    if (strlen($name) > 0 && strlen($hostname) > 0 && $port > 0 &&
		    $port <= 65536) {
		WebService::addOutput(
			$requestVars['name'], $requestVars['hostname'], $requestVars['port']
		);
	    } else {
		RestUtils::sendResponse(400);
	    }
	}
	// Unknown POST request
	RestUtils::sendResponse(400);
	break;
    // DELETE functions
    case 'delete':
	//Check uri
	if (preg_match("/streams\/(\d+)$/", $data->getURI(), $matches)) {
	    // Get stream id
	    $id = $matches[1];
	    WebService::remove(
		    $id
	    );
	}
	// Unknown POST request
	RestUtils::sendResponse(400);
	break;

    default:
	// Unknown HTTP method (?!)
	RestUtils::sendResponse(400);
	break;
}
?>
