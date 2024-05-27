var Forums 	= function()
{

	var sample_datatable 	= function()
	{
		$('#forum_table').DataTable();
	};

	return {
		datatable 		: function()
		{
			sample_datatable();
		}
	}

}();