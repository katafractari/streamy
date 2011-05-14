// Init variables + functions + actions
var streams = new Array();
var newStreamNameError = false;
var newStreamURLError = false;

var raisedErrors = new Array();
raisedErrors[DUPLICATE_NAME_ERROR] = false;
raisedErrors[DUPLICATE_URL_ERROR] = false;
raisedErrors[INVALID_NAME_ERROR] = false;
raisedErrors[INVALID_URL_ERROR] = false;
raisedErrors[NO_URL_ERROR] = false;
raisedErrors[NO_NAME_ERROR] = false;

// On document ready
$(document).ready(function() {
	status();
	getStreams();
	prettyForms()
	setInterval(status, 10000);		
});
