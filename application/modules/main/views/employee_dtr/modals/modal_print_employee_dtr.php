<form id="form_leave_application" class="p-b-md">
	<div class="form-float-label" >
		<div class="row m-n">				
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="datepicker_start" id="date_range_from" name="date_range_from"/>
					<label for="date_range_from" class="active">Start Date <span class="required"> * </span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="datepicker_end" id="date_range_to" name="date_range_to"/>
					<label for="date_range_to" class="active">End Date <span class="required"> * </span></label>
				</div>
			</div>
		</div>
	</div>		
	<div class="md-footer default">
		<a class=" btn-flat cancel_modal">Cancel</a>
	    <a href="javascript:;" class="btn btn-success" id="print_dtr" target ="_blank" value="<?php echo BTN_SAVE ?>">Print</a>
	 </div>
</form>
<script> 
$(document).ready(function(){
	$('#print_dtr').off('click');
	$('#print_dtr').on('click',function(e){
		e.preventDefault();
		var date_range_from = $('#date_range_from').val();
		var date_range_to = $('#date_range_to').val();
		
		if(date_range_from != "" && date_range_to != "")
		{
			modal_print_employee_dtr.closeModal();
			window.open($base_url + 'main/employee_dtr/print_employee_dtr/' + dateFormat(date_range_from, 'yyyy-mm-dd') + '/' + dateFormat(date_range_to, 'yyyy-mm-dd'), '_blank');				
		}
		else{
			if(date_range_from == "" && date_range_to != "")
			{
				notification_msg("<?php echo ERROR ?>", "<b>Start Date</b> is required.");
			}
			else if(date_range_from == "" && date_range_to != "")
			{
 				notification_msg("<?php echo ERROR ?>", "<b>End Date</b> is required.");
			}
			else
			{
				notification_msg("<?php echo ERROR ?>", "<b>Start Date</b> and <b>End Date</b> is required.");
			}
			
		}
	});
});
</script>