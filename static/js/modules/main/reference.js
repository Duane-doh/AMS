var Reference = function()
{

	var init 			= function()
	{
		on_load();
		on_change();
	}

	var on_load 		= function()
	{
		set_link_type();
	}

	var on_change 		= function()
	{
		$("#link_type_id").change(function(){
			set_link_type();
		});
	}

	var set_link_type 	= function()
	{
		$(".external-link-row").hide();
		$(".attachment-row").hide();
		$(".kms-asset-row").hide();
		$(".kms-asset-record-row").hide();

		var link_type_id = $("#link_type_id").val();

		switch(link_type_id)
		{
			case 'KM_ASSETS':
				$(".kms-asset-row").show();
				$(".kms-asset-record-row").show();
			break;

			case 'EXTERNAL_LINK':
				$(".external-link-row").show();
			break;

			case 'ATTACHMENT':
				$(".attachment-row").show();
			break;    
		}
	}

	return {
		init : function()
		{
			init();
		}
	}

}();