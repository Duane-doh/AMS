<form id="form_request">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="row m-t-sm">
		<div class="row p-l-sm"><label class="dark font-md">Request Type</label></div>
		<div class="col s3 m-r-n-lg">
			<input type="radio" class="labelauty" name="request_major_type"  value="<?php echo REQUEST_ATTENDANCE ?>" data-labelauty="Attendance" checked/>
		</div>	
		 <div class="col s3">
			<input type="radio" class="labelauty" name="request_major_type"  value="<?php echo REQUEST_CERTIFICATION ?>" data-labelauty="Certification"/>
		 </div>	
	</div>
	<div class="none" id="request_type_group" class="row m-t-md">
		<div class="form-float-label" >
			<div class="row b-t b-light-gray m-n">
			  <div class="col s12">
				<div class="input-field">
				  <label for="request_sub_type" class="active">Sub Request Type</label>
				  <select id="request_sub_type" name = "request_sub_type" class="selectize" placeholder="Select Sub Request Type">
					<option value="">Select Sub Request Type</option>
					<option value="<?php echo TYPE_REQUEST_ATTENDANCE_LEAVE_APPLICATION ?>">Leave Application</option>
					<option value="<?php echo TYPE_REQUEST_ATTENDANCE_OFFICIAL_BUSINESS ?>">Offical Business</option>
					<option value="<?php echo TYPE_REQUEST_ATTENDANCE_MANUAL_ADJUSTMENT ?>">Manual Ajustment</option>
				  </select>
				</div>
				</div>
			 </div>
		</div>
	</div>
	<div class="none" id="leave_group">
		<div class="form-float-label" >
			<div class="row">
			  	<div class="col s7">
					<div class="input-field">
				  		<label for="leave_type" class="active">Leave Type</label>
				  		<select id="leave_type" name="leave_type" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Leave">
							<option value="">Select Leave Type</option>
							<?php if (!EMPTY($leave_types)): ?>
								<?php foreach ($leave_types as $type): ?>
									<option value="<?php echo $type['leave_type_id'] ?>"><?php echo $type['leave_type_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
				  		</select>
					</div>
			  	</div>
			  	<div class="col s5">
				  <div class="input-field p-sm">
				  	<label for="requested_flag" class="active">Communication</label>
				   	<input type="checkbox" class="labelauty" name="requested_flag" id="requested_flag" value="1" data-labelauty="Not Requested|Requested"  <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				  </div>
			    </div>
			</div>
			<div class="row">
				<div class="col s4">
					<div class="input-field">
						<input id="no_of_days" name="no_of_days" type="text" class="validate">
						<label for="no_of_days" >No. of Days</label>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<input id="date_from" name="date_from" type="text" class="validate datepicker datepicker_start">
						<label for="date_from">From</label>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
						<input id="date_to" name="date_to" type="text" class="validate datepicker datepicker_end">
						<label for="date_to">To</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					  	<label for="leave_details">Specific Details</label>
	      				<textarea name="leave_details" class="materialize-textarea" id="details" value=""></textarea>
					</div>
				 </div>	
			</div>
		</div>
	</div>
	<div class="none" id="official_business_group">
		<div class="form-float-label" >
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<input id="ob_date_from" name="ob_date_from" type="text" class="validate datepicker date_start">
						<label for="ob_date_from">From</label>
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<input id="ob_date_to" name="ob_date_to" type="text" class="validate datepicker date_end">
						<label for="ob_date_to">To</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
					  	<label for="specific_details">Specific Details</label>
	      				<textarea name="specific_details" class="materialize-textarea" id="specific_details" value=""></textarea>
					</div>
				 </div>	
			</div>
		</div>
	</div>
	<div class="none" id="certification_group">
		<div class="form-float-label" >
		<div class="row b-t b-light-gray">
			  <div class="col s12">
				<div class="input-field">
				  <label for="cert_type" class="active">Certification Type</label>
				  <select id="cert_type" name="cert_type" class="selectize" placeholder="Select Certification Type">
					<option value="">Select Certification Type</option>
					<?php if (!EMPTY($cert_types)): ?>
						<?php foreach ($cert_types as $type): ?>
							<option value="<?php echo $type['certification_type_id'] ?>"><?php echo $type['certification_type_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
				  </select>
				</div>
			  </div>
			</div>
			<div class="row">
			<div class="col s12">
				<div class="input-field">
				  	<label for="cert_details">Specific Details</label>
      				<textarea name="cert_details" class="materialize-textarea" id="details"></textarea>
				</div>
			 </div>	
			 
			</div>
		</div>
	</div>
	
</form>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button id="save_request" class="btn" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
 </div>
<script>
$(document).ready(function(){

	$('#request_type_group').removeClass('none');
	
	$('input[name="request_major_type"]').off('change');
	$('input[name="request_major_type"]').on('change', function(e) {
		var selected = $('input[name="request_major_type"]:checked').val();
	    if(selected === "<?php echo REQUEST_ATTENDANCE ?>"){
	    	$('#leave_group').addClass('none');
	    	$('#certification_group').addClass('none');
	    	$('#request_type_group').removeClass('none');
	    	$('#official_business_group').addClass('none');

	    	 var value = $('#request_sub_type').val();
	    	 if(value !== "")
	    	 {
				if(value === "<?php echo TYPE_REQUEST_ATTENDANCE_LEAVE_APPLICATION ?>"){
				    	$('#leave_group').removeClass('none');
				    	$('#certification_group').addClass('none');
				    	$('#request_type_group').removeClass('none');
			    		$('#official_business_group').addClass('none');
				}
				else
				{
						$('#leave_group').addClass('none');
				    	$('#certification_group').addClass('none');
				    	$('#request_type_group').removeClass('none');
			    		$('#official_business_group').removeClass('none');
				}

	    	 }
	    	
	    }
	    else if(selected === "<?php echo REQUEST_CERTIFICATION ?>"){
	    	$('#leave_group').addClass('none');
	    	$('#certification_group').removeClass('none');
	    	$('#request_type_group').addClass('none');
	    	$('#official_business_group').addClass('none');
	    	
	    }
	    else
	    {
	    	$('#leave_group').addClass('none');
	    	$('#certification_group').removeClass('none');
	    	$('#request_type_group').addClass('none');
	    	$('#official_business_group').addClass('none');
	    }

	});
	$('#request_sub_type').off('change');
	$('#request_sub_type').on('change', function(e) {
		var value = $('#request_sub_type').val();
		if(value === "<?php echo TYPE_REQUEST_ATTENDANCE_LEAVE_APPLICATION ?>"){
		    	$('#leave_group').removeClass('none');
		    	$('#certification_group').addClass('none');
		    	$('#request_type_group').removeClass('none');
	    		$('#official_business_group').addClass('none');
		}
		else
		{
				$('#leave_group').addClass('none');
		    	$('#certification_group').addClass('none');
		    	$('#request_type_group').removeClass('none');
	    		$('#official_business_group').removeClass('none');
		}
	});
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_request').parsley();
	jQuery(document).off('click', '#save_request');
	jQuery(document).on('click', '#save_request', function(e){	
		$("#form_request").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_request');
	jQuery(document).on('submit', '#form_request', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_request').serialize();
		  	button_loader('save_request', 1);
		  	var option = {
					url  : $base_url + 'main/employee_requests/process_requests',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_employee_request.closeModal();
							load_datatable('table_employee_request', '<?php echo PROJECT_MAIN ?>/employee_requests/get_employee_requests',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_request', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>