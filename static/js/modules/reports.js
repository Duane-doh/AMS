$(function(){
	filter_report();
	
	function filter_report()
	{
		
		$('.period-row').hide();
		$('.document-type-row').hide();
		$('.activity-type-row').hide();
		$('.indicator-row').hide();
		$('.division-row').hide();
		$('.group-row').hide();
		$('.communications-row').hide();
		$('.tags-row').hide();
		$('.document-status-row').hide();
		$('.individual-row').hide();
		
		
		var report = $("#report_id").val();
		
		switch(report)
		{
			case 'document_workflow_status_analysis':
				$('.period-row').show();
				$('.document-type-row').show();
				$('.tags-row').show();
				$('.division-row').show();
			break;
		}
		
	}
	
	$("#report_id").on("change", function(){
		filter_report();
	});

	base_url = $("#base_url").val();
	
	
	$('#generate_btn').on("click", function(e){
		e.preventDefault();
		var report_id = $("#report_id").val();
		var generate_type = $('input[name="generate"]:checked').val();
		if(report_id !=="")
		{
			var link = base_url + 'reports/kms_reports/generate/?'
			if(generate_type == "excel")
			{	
				$("#modal_report").removeClass("md-show");
				window.location = link + $('#reports_form').serialize();
			}
			else
			{
				var data	= $('#reports_form').serialize();
				$("#modal_report_content").isLoading({position: "overlay"});
				$.post(link, data, function(result){
					
					$("#modal_report_content").html(result);
					$(".md-content").removeAttr("style");
					$("#modal_report").center();
					$(".md-content").draggable({
					    handle: "div.md-header",
					    containment: "#content-wrapper", scroll: false
					});
				});
			
			}
		}
		else
		{
			$("#modal_report").removeClass("md-show");
			notification_msg("error", "Report type is required.");
		}
});
	

	
	
});
