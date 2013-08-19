/*
 * init.js
 * Init variables + functions + actions
 */

var streams = new Array();
var outputs = new Array();
var outputsDropDownOpen = false;

var raisedErrors = new Array();
raisedErrors[DUPLICATE_NAME_ERROR] = false;
raisedErrors[DUPLICATE_URL_ERROR] = false;
raisedErrors[INVALID_NAME_ERROR] = false;
raisedErrors[INVALID_URL_ERROR] = false;
raisedErrors[NO_URL_ERROR] = false;
raisedErrors[NO_NAME_ERROR] = false;

// Global
var playing = false;

// On document ready
$(document).ready(function() {
	status();
	getStreams();
	getOutputs();
	prettyForms()
	setInterval(status, 10000);		

	// Define hooks
	$("#outputs").click(function() {
		if(!outputsDropDownOpen)
		{
			//getOutputs();
			outputsDropDownOpen = true;
		}
		else
		{
			outputsDropDownOpen = false;
		}
	});
});
