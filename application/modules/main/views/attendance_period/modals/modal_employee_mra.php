<form id="form_employee_mra">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="attendance_period_hdr_id" value="<?php echo $attendance_period_hdr_id ?>"/>
	<div class="form-float-label">		

	  	<div class="row m-n">	
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="lwop_ut_hr" data-parsley-type="number" id="lwop_ut_hr" value="<?php echo isset($mra_summary['lwop_ut_hr'])? $mra_summary['lwop_ut_hr']: '0' ?>"/>
				    <label for="lwop_ut_hr">Hours</label>
			  	</div>
		    </div>
		    <div class="col s6">
			  	<div class="input-field">
				   	<input type="text" class="validate" name="lwop_ut_min" data-parsley-type="number" id="lwop_ut_min" value="<?php echo isset($mra_summary['lwop_ut_min'])? $mra_summary['lwop_ut_min'] : '0' ?>"/>
				    <label for="lwop_ut_min">Minutes</label>
			  	</div>
		    </div>
	  	</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn btn-success " type="submit" id="save_employee_mra" value="SAVING">SAVE</button>
	</div>
</form>
<script>

$(function (){

	
	$("label").addClass('active');

 	jQuery(document).off('submit', '#form_employee_mra');
	jQuery(document).on('submit', '#form_employee_mra', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_employee_mra').serialize();
			
			var	process_url = $base_url + 'main/attendance_mra/process_employee_mra';
			
		  	button_loader('save_employee_mra', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_employee_mra.closeModal();
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_employee_mra', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>