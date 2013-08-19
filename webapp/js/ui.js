(function($){
    
    // jQuery autoGrowInput plugin by James Padolsey
    // See related thread: http://stackoverflow.com/questions/931207/is-there-a-jquery-autogrow-plugin-for-text-fields
        
        $.fn.autoGrowInput = function(o) {
            
            o = $.extend({
                maxWidth: 1000,
                minWidth: 0,
                comfortZone: 70
            }, o);
            
            this.filter('input:text').each(function(){
                
                var minWidth = o.minWidth || $(this).width(),
                    val = '',
                    input = $(this),
                    testSubject = $('<tester/>').css({
                        position: 'absolute',
                        top: -9999,
                        left: -9999,
                        width: 'auto',
                        fontSize: input.css('fontSize'),
                        fontFamily: input.css('fontFamily'),
                        fontWeight: input.css('fontWeight'),
                        letterSpacing: input.css('letterSpacing'),
                        whiteSpace: 'nowrap'
                    }),
                    check = function() {
                        
                        if (val === (val = input.val())) {return;}
                        
                        // Enter new content into testSubject
                        var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,'&nbsp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        testSubject.html(escaped);
                        
                        // Calculate new width + whether to change
                        var testerWidth = testSubject.width(),
                            newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
                            currentWidth = input.width(),
                            isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
                                                 || (newWidth > minWidth && newWidth < o.maxWidth);
                        
                        // Animate width
                        if (isValidWidthChange) {
                            input.width(newWidth);
                        }
                        
                    };
                    
                testSubject.insertAfter(input);
                
                $(this).bind('keyup keydown blur update', check);
                
            });
            
            return this;
        
        };
        
    })(jQuery);


// Events
// Play & stop
function onClickAddOutput() {
	$("#addOutputDialog").dialog(
	{
		"title": "Add a new output",
		"width": 330,
		"height": 200,
		buttons: 
		{ 
			"Add": function() 
			{ 
				addOutput(
					$(this), 
					$("#newOutputName").val(),
					$("#newOutputHost").val(),
					$("#newOutputPort").val()
					); 
			}
		} 
	});
	// Clear inputs just in case
	$("#newOutputName").val("");
	$("#newOutputHost").val("");
	$("#newOutputPort").val("6600");
	$("#dialogError").val("");
}

function onClickChangeState() {
	var command = playing ? "pause" : "play";
	$.ajax({
		type: "POST",
		url: API_BASE_URL + command,
		error: function(jqXHR, textStatus, errorThrown) {
			var response = jqXHR.responseText;
		},
		success: function(xml, textStatus, jqXHR) {
			getOutputs();
			status();
		}
	});
}
