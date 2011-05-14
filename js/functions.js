/*
 * JavaScript REST client API
 *
 */

function showAddStreamDialog()
{
	$("#newStreamName").click(function() {
		$(this).css("color", "black");
	});
	$("#newStreamURL").click(function() {
		$(this).css("color", "black");
	});

	$("#addStreamDialog").dialog(
	{
		"title": "Add a new stream",
		"width": 300,
		"height": 200,
		buttons: 
		{ 
			"Add": function() 
			{ 
				addStream(
					$(this), 
					$("#newStreamName").val(),
					$("#newStreamURL").val(),
					false
				); 
			},
			"Add and Play": function() 
			{ 
				addStream(
					$(this), 
					$("#newStreamName").val(),
					$("#newStreamURL").val(),
					true
				); 
			}
		} 
	});
	// Clear inputs just in case
	$("#newStreamName").val("");
	$("#newStreamURL").val("");
	$("#dialogError").val("");

	// Make inputs auto resizable
	//$("#newStreamName").autoGrowInput();
	//$("#newStreamURL").autoGrowInput();
}

/*
 *  Function setInputError
 *	Arguments: 
 *		id: id of input field
 *		error: an error object thrown that was thrown
 */
 
function setInputError (error)
{
	// Raise an error
	if(!raisedErrors[error.code])
		raisedErrors[error.code] = true;
	else
		return;

	var inputField;
	if(error.parameter == "name")
		inputField = $("#newStreamName");
	else if(error.parameter == "url")
		inputField = $("#newStreamURL");
	else
		return;

	// Set red text only when text is present in the input field
	$(inputField).css("color", "red");

	if($("#dialogError").html().length != 0)
		$("#dialogError").append("<br>");
	console.log("code:"+error.code);
	$("#dialogError").append(errorMessages[error.code]);
}

/*
 *  Function unsetInputError
 *	Arguments: 
 *		id: id of input field
 *		error: an error object thrown that was thrown
 */
 
function unsetInputError (inputFieldName)
{
	var inputField;
	if(inputFieldName == "name")
		inputField = $("#newStreamName");
	else if(inputFieldName == "url")
		inputField = $("#newStreamURL");
	else
		return;
	
	// Unraise an error
	raisedErrors[error.code] = false;

	$(inputField).css("color", "black");
}

 /*
  *  Function name: addStream
  *  Arguments:
  *  	dialog: the dialog object
  *		name: name of stream
  *		url: url of stream
  *		playOnAdd: whether to play the stream on successfull add
  *	 Returns:
  *	 	Success: id of the new stream
  *	 	Error: 1
  */
  
function addStream(dialog, name, url, playOnAdd)
{
	// The return code
	var ret = null;
	var a = 0;

	// Check the function arguments
	if(typeof dialog != "object")
		return ret;
	else if(typeof name != "string")
		return ret;
	else if(typeof url != "string")
		return ret;

	// Unraise all errors before proceding
	for (var i = 0; i <raisedErrors.length; i++) {
		raisedErrors[i] = false;
	}

	if(name.length > 0)
	{
		if(url.length > 0)
		{
			// Url is OK, so is name
			var ret = null;

			$.ajax({
				type: "POST",
				url: "/streammanager/api/streams",
				data: { 
					name: name, 
					url: url
				},
				error: function(jqXHR, textStatus, errorThrown) {
					var response = jqXHR.responseText;
					$("#dialogError").html("");
					if($(response).find("errors"))
					{
						$(response).find("error").each(function() {
							// Get a single error representation
							var errorObj =	{ 
								"parameter": $(this).find("parameter").text(),
								"code": $(this).find("code").text()
							};
	
							setInputError({ 
								"code": errorObj.code, 
								"parameter": errorObj.parameter }
							);
							return;
						});
					}

				},
				success: function(xml, textStatus, jqXHR) {
					//alert(new XMLSerializer().serializeToString(xml));
					var id = $(xml).find("id").text();
					$(dialog).dialog("close");
					getStreams();
					if(playOnAdd)
						play(id);
				}
			});
		}
		else
		{
			$("#dialogError").html("");
			if(name.length == 0)
				setInputError({ "code": NO_NAME_ERROR, "parameter": "name" });
			setInputError({ "code": NO_URL_ERROR, "parameter": "url" });
		}
	}
	else
	{	
		$("#dialogError").html("");
		if(url.length == 0)
			setInputError({ "code": NO_URL_ERROR, "parameter": "url" });
		setInputError({ "code": NO_NAME_ERROR });
	}

	// Return a code
	// NULL or new stream ID
	return ret;
}

function status()
{
	$.ajax({
		type: "GET",
		url: "/streammanager/api/status",
		dataType: "xml",
		success: function(xml) {
			var name = $(xml).find("name").text();
			var url = $(xml).find("url").text();
			var title = $(xml).find("title").text();
			var coverUrl = $(xml).find("coverUrl").text();

			if(coverUrl && 
				$("#artCover").attr("src") != 'images/streams/'+ coverUrl &&
					coverUrl != '')
			{
				$("#artCover").attr('src', 'images/streams/'+ coverUrl);
			}
			else if(!coverUrl)
			{
				$("#artCover").attr('src', '');
			}
				
			$("#name").html(name);
			$("#url").html(url);
			$("#title").html(title);
		}
	});
}

// function for retrieving the list of streams via REST
function getStreams()
{
	$.ajax({
		type: "GET",
		url: "/streammanager/api/streams",
		dataType: "xml",
		success: function(xml) {
			var html = "";
			var index = 0;
			streams = new Array();
			$(xml).find("stream").each(function() {
				var stream = new Object();
				stream['name'] = $(this).find("name").text();
				stream['url'] = $(this).find("url").text();
				stream['id'] = $(this).find("id").text();
				index++;

				streams.push(stream);
			});
			generateStreamsHtml();
		}
	});
}

// for playing a stream
function play(id)
{
	$.ajax({
		type: "PUT",
			url: "/streammanager/api/streams/"+ id +"/play",
			// data: { test: "krneki" },
		dataType: "xml",
		error: function(jqXHR, textStatus, errorThrown) {
			console.log("error");
		},
		success: function(xml, textStatus, jqXHR) {
			setTimeout(status, 300);
		}
	});
}

function remove(id) 
{
	var success;
	$.ajax({
		type: "DELETE",
		url: "/streammanager/api/streams/"+ id,
		data: { 
			"id": id
		},
		error: function(jqXHR, textStatus, errorThrown) {
			var response = jqXHR.responseText;
		},
		success: function(xml, textStatus, jqXHR) {
			getStreams();
		}
	});
}
