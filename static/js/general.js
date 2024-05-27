var General = function()
{

	var ajaxRequest = function(options)
	{
		/* Sets of predefined config for ajax request */
		var predefined = {
				dataType   : "json",
				method     : "POST",
				error 	   : function(jqXHR)
				{
					notification_msg("error", 'Internal Error!');
				}
		}
		/* Merge the two configs */
	   var final_option = $.extend(predefined, options);

	   return $.ajax(final_option);
	};

	return {
		ajax  : function(options){
			return ajaxRequest(options);
		}
	}

}();
