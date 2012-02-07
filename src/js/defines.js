/*
 * Defines
 *
 */

// Globals
var API_BASE_URL = "/streammanager/src/api/";

// Language (except error messages)
var LANG_DUPLICATE_NAME = "*A stream with that name already exists*"

// Errors
//
// Error code mappings
var DUPLICATE_NAME_ERROR = 0;
var DUPLICATE_URL_ERROR = 1;
var INVALID_NAME_ERROR = 2;
var INVALID_URL_ERROR = 3;
var NO_NAME_ERROR = 4;
var NO_URL_ERROR = 5;

// Error messages
var errorMessages = new Array();
errorMessages[DUPLICATE_NAME_ERROR] = "A stream with that name already exists.";
errorMessages[DUPLICATE_URL_ERROR] = "A stream with that URL already exists.";
errorMessages[INVALID_NAME_ERROR] = "Invalid name.";
errorMessages[INVALID_URL_ERROR] = "Invalid URL.";
errorMessages[NO_URL_ERROR] = "Please enter an URL.";
errorMessages[NO_NAME_ERROR] = "Please enter a name.";
