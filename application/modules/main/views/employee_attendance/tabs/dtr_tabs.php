<div class="container p-t-n">
  <div class="section panel p-sm p-t-n">
      <div class="row teal">
        <div class="col s5">
          <ul class="tabs dtr_tab teal col s12">
            <li class="tab col s6 active"><a href="<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_dtr_list/".$action."/".$id."/".$token."/".$salt."/".$module; ?>">Attendance List</a></li>
            <li class="tab col s6"><a href="<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_time_logs/".$action."/".$id."/".$token."/".$salt."/".$module; ?>">Time Logs</a></li>
          </ul>
        </div>
        <div id="dtr_tab_content" class="col s12 white p-t-md"></div>
      </div>           
  </div>
</div>
<script type="text/javascript">
$(function (){

$('#dtr_tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_dtr_list/".$action."/".$id."/".$token."/".$salt."/".$module;?>')

	$('.dtr_tab > li > a').click(function(event){

		event.preventDefault();

		$('#dtr_tab_content').isLoading();
		var href = $(this).attr('href');
		$.get(href, function(result){
        if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker').length > 0)
            {
                $('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');
            }  
			$('#dtr_tab_content').html(result);
			 $('#dtr_tab_content').isLoading( "hide" );
		});

        $('.dtr_tab > li > a').removeClass('active');
        $(this).addClass('active');
	});
})
</script>