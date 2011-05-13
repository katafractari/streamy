// Utility functions

// Generate HTML for a single stream
function generateStreamsHtml()
{
	var html = "";
	var current = "even";
	for(var i = 0; i < streams.length; i++)
	{
		if(i % 2 == 0)
			current = "even";
		else
			current = "odd";
			
		html += '<div class="'+ current +'" id="'+ i +'">';

		html += '<span class="icon">';
		html += '<img onmouseover="this.style.cursor=\'crosshair\'" onclick="play('+ streams[i]["id"] +')" src="images/radio.png">&nbsp;';
		html += '</span>';

		html += '<span class="name">';
		html += streams[i]['name'];
		html += '</span>';

		html += '<span class="edit">';
		html += '<img onmouseover="this.style.cursor=\'crosshair\'" onclick="remove('+ streams[i]["id"] +')" src="images/remove.png">&nbsp;';
		html += '</span>';

		html += '</div>';
	}
	$("#streams").html("");
	$("#streams").html(html);
}
