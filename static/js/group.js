var Group = function()
{	
 	var colorpick 	= function()
	{

		if( $('#group_color').val() != '' )
		{
			$('#group_color').css('backgroundColor', '#' + $('#group_color').val() );
		}

		$('#group_color').ColorPicker({
			onSubmit: function(hsb, hex, rgb, el) {
				$(el).val(hex);
				$(el).ColorPickerHide();
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			},
			onChange: function (hsb, hex, rgb) {
				$('#group_color').val('#'+hex);
				$('#group_color').css('backgroundColor', '#' + hex);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
	}

	return {
		colorpick : function()
		{
			colorpick();
		}
	}	
}();