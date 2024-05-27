<form id="form_leave_application" class="p-b-md">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="employee_id"  id="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" name="type" value="<?php echo $leave_type ?>"/>
	<div id="div_basic">	
		<div class="form-float-label">
			<div class="row m-n">
				<div class="col s6 p-b-xl">
					<div class="input-field">
					  	<label for="commutation_flag" class="active">Commutation</label>
	      				<div class='switch'>
						    <label class="font-sm cursor-pointer">
						        Not Requested
						        <input name='commutation_flag'  id='commutation_flag' type='checkbox' value='Y'> 
						        <span class='lever'></span>Requested
						    </label>
						</div>
					</div>
				 </div>
				 <div class="col s6">
					<div class="input-field">
					  	<label for="monetize_flag" class="active">Monetization</label>
	      				<div class='switch'>
						    <label class="font-sm cursor-pointer">
						        No
						        <input name='monetize_flag'  id='monetize_flag' type='checkbox' value='Y'> 
						        <span class='lever'></span>Yes
						    </label>
						</div>
					</div>
				 </div>	
			</div>
			<div class="row m-n">
			 	<div class="col s12">
					<div class="input-field">
						<input id="no_of_days" name="no_of_days" type="text" class="validate" value = "" readonly/>
						<label for="no_of_days" id ="no_of_days_label"class="active">Number of Days <span class="required"> * </span></label>
					</div>
				</div>
			</div>
		</div>
		<div id="div_date_range">	
			<div class="form-float-label" >
				<div class="row m-n">				
					<div class="col s6">
						<div class="input-field">
							<input id="date_from" name="date_from" type="text" class="validate datepicker_start"/>
							<label for="date_from">Start Date <span class="required"> * </span></label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<input id="date_to" name="date_to" type="text" class="validate datepicker_end"/>
							<label for="date_to">End Date <span class="required"> * </span></label>
						</div>
					</div>
				</div>
			</div>		
		</div>
		
	</div>
	<div id="div_commutation">
		<div id="leave_break_down">	
			<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">Leave Break Down</label></div>
			
		</div>
		<!-- marvin : start : disabled -->
		<!--<div id="div_reason" class="<?php echo (isset($leave_type) AND $leave_type != LEAVE_TYPE_VACATION) ? 'none':''?>">	
			<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">Reason <span class="required"> * </span></label></div>
			<div class="row b-n p-b-sm">				
				<div class="col s5 b-n p-l-lg">
						<input type="radio" class="labelauty" name="reason"  value="S" data-labelauty="To seek employment" />
				</div>
				<div class="col s7">
						<input type="radio" class="labelauty" name="reason"  value="O" data-labelauty="Others" />
				</div>
			</div>
			<div id="div_text_reason"  class="none">
				<div class="form-float-label" >
					<div class="row m-n  b-t b-light-gray">
						<div class="col s12">
							<div class="input-field">
							  	<label for="vl_details">Specific Reason <span class="required"> * </span></label>
			      				<textarea name="vl_details" class="materialize-textarea" id="vl_details" value=""></textarea>
							</div>
						 </div>	
					</div>
				</div>	
			</div>
		</div>-->
		<!-- marvin : end : disabled -->
		
		<!--<div id="div_location" class="none">-->	
		
		<!-- marvin : start : add class -->
		<div id="div_location" class="<?php echo (isset($leave_type) AND $leave_type != LEAVE_TYPE_VACATION) ? 'none':''?>">				
		<!-- marvin : end : add class -->
		
			<!--<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">Location <span class="required"> * </span></label></div>-->
			
			<!-- marvin : start : change to in case of vacation leave -->
			<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">In case of Vacation Leave <span class="required"> * </span></label></div>
			<!-- marvin : end : change to in case of vacation leave -->
			<div class="row b-n p-b-sm">				
				<div class="col s5 b-n p-l-lg">
						<input type="radio" class="labelauty" name="location"  value="P" data-labelauty="Within the Philippines" />
				</div>
				<div class="col s7">
						<!--<input type="radio" class="labelauty" name="location"  value="A" data-labelauty="Abroad" />-->
						
						<!-- marvin : start : change to others -->
						<input type="radio" class="labelauty" name="location"  value="A" data-labelauty="Abroad" />
						<!-- marvin : end : change to others -->
				</div>
			</div>
			<!--<div id="div_text_location" class="none">-->
			
			<!-- marvin : start : remove class none -->
			<div id="div_text_location" class="">
			<!-- marvin : end : remove class none -->
			
				<div class="form-float-label" >
					<div class="row m-n b-t b-light-gray">
						<div class="col s12">
							<div class="input-field">
							  	<!--<label for="vl_location">Specific Location <span class="required"> * </span></label>-->
								
								<!-- marvin : start : change to specify place -->
							  	<label for="vl_location">Specify Place <span class="required"> * </span></label>
								<!-- marvin : end : change to specify place -->
			      				<textarea name="vl_location" class="materialize-textarea" id="vl_location" value=""></textarea>
							</div>
						 </div>	
					</div>		
				</div>	
			</div>		
		</div>
		
		<div id="div_sick_leave" class="<?php echo (isset($leave_type) AND $leave_type != LEAVE_TYPE_SICK) ? 'none':''?>">
			<div class="form-float-label" >
				<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">Sick Leave Type <span class="required"> * </span></label></div>
				<div class="row b-n p-b-sm">				
					<div class="col s5 b-n p-l-lg">
						<input type="radio" class="labelauty" name="sick_leave"  value="H" data-labelauty="In Hospital" />
					</div>
					<div class="col s7">
						<input type="radio" class="labelauty" name="sick_leave"  value="O" data-labelauty="Out Patient" />
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<div class="input-field">
						  	<!--<label for="sl_details">Specific Details <span class="required"> * </span></label>-->
							
							<!--marvin-->
						  	<label for="sl_details">Specify Illness <span class="required"> * </span></label>
							<!--marvin-->
		      				<textarea name="sl_details" class="materialize-textarea" id="sl_details" value=""></textarea>
						</div>
					 </div>	
				</div>	
			</div>		
		</div>

		<!-- marvin : start : include study leave -->
		<div id="div_study_leave" class="<?php echo (isset($leave_type) AND $leave_type != LEAVE_TYPE_STUDY) ? 'none':''?>">
			<div class="form-float-label" >
				<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">In case of Study Leave <span class="required"> * </span></label></div>
				<div class="row b-n p-b-sm">				
					<div class="col s6 b-n p-l-lg">
						<input type="radio" class="labelauty" name="study_type_id"  value="M" data-labelauty="Completion of Master's Degree" />
					</div>
					<div class="col s6">
						<input type="radio" class="labelauty" name="study_type_id"  value="B" data-labelauty="BAR/Board Examination Review" />
					</div>
				</div>
			</div>
		</div>
		<!-- marvin : end : include study leave -->
		
		<!-- marvin : start : include special privilege leave -->
		<div id="div_location" class="<?php echo (isset($leave_type) AND $leave_type != LEAVE_TYPE_SPECIAL_PRIVILEGE) ? 'none':''?>">				
			<div class="row p-l-sm p-t-sm"><label class="font-normal font-md">In case of Special Privilege Leave <span class="required"> * </span></label></div>
			<div class="row b-n p-b-sm">				
				<div class="col s5 b-n p-l-lg">
					<input type="radio" class="labelauty" name="location"  value="P" data-labelauty="Within the Philippines" />
				</div>
				<div class="col s7">
					<input type="radio" class="labelauty" name="location"  value="A" data-labelauty="Abroad" />
				</div>
			</div>
			<div id="div_text_location" class="">
				<div class="form-float-label" >
					<div class="row m-n b-t b-light-gray">
						<div class="col s12">
							<div class="input-field">
							  	<label for="spl_location">Specify Place <span class="required"> * </span></label>
			      				<textarea name="spl_location" class="materialize-textarea" id="spl_location" value=""></textarea>
							</div>
						 </div>	
					</div>		
				</div>	
			</div>		
		</div>
		<!-- marvin : end : include special privilege leave -->
		
		<!-- marvin : start : include special leave benefits for women -->
		<div id="div_location" class="<?php echo (isset($leave_type) AND $leave_type != LEAVE_TYPE_SPECIAL_BENEFITS_WOMEN) ? 'none':''?>">				
			<div id="" class="">
				<div class="form-float-label" >
					<div class="row m-n b-t b-light-gray">
						<div class="col s12">
							<div class="input-field">
								<label for="sbl_details">Specify Illness <span class="required"> * </span></label>
								<textarea name="sbl_details" class="materialize-textarea" id="sbl_details" value=""></textarea>
							</div>
						 </div>
					</div>		
				</div>	
			</div>		
		</div>
		<!-- marvin : end : include special leave benefits for women -->
	</div>	
	<?php if($action != ACTION_VIEW):?>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn btn-success" id="save_leave_application" value="<?php echo BTN_SAVE ?>">Submit</button>
	 </div>
	<?php endif;?>
</form>
<script> 
$(document).ready(function(){

	$('#date_from,#date_to').off('change');
	$('#date_from,#date_to').on('change',function(e){
		var date_from = $('#date_from').val();
		var date_to = $('#date_to').val();
		
		/*===== marvin : start : include nature_of_deduction =====*/
		var nature_of_deduction = <?php echo $nature_of_deduction; ?>;
		/*===== marvin : end : include nature_of_deduction =====*/
		
		var data ={
			'date_from': date_from,
			'date_to': date_to,
			
			/*===== marvin : start : include nature_of_deduction =====*/
			'nature_of_deduction' : nature_of_deduction
			/*===== marvin : end : include nature_of_deduction =====*/
		}
		if(date_from != "" && date_to != "")
		{
			$('#leave_columns_break_down').remove();		      	
			$.post($base_url + 'main/employee_dtr/get_leave_breakdown',data, function(result) {
				$('#leave_columns_break_down').remove();
			  	$('#leave_break_down').append(result.breakdown);
		      	$('#no_of_days').val(result.total_days);
		      	$('#no_of_days_label').addClass('active');
				/*===== davcorrea : start : select to load values in form =====*/
				$('#no_of_days').select();
				/*===== davcorrea : end : select to load values in form =====*/
				
		      	selectize_init();
		     }, 'json'); 
		}
	});
	$('input[name="commutation_flag"]').off('change');
	$('input[name="commutation_flag"]').on('change',function(e){
		var selected = $('input[name="commutation_flag"]:checked').val();
		if(selected)
		{
			$('#div_date_range').addClass('none');
			$('#div_commutation').addClass('none'); 
			$('input[name="monetize_flag"]').prop('disabled',true);
			$('#no_of_days').prop('readonly',false);
		}
		else
		{
			$('#div_date_range').removeClass('none');
			$('#div_commutation').removeClass('none');
			$('input[name="monetize_flag"]').prop('disabled',false);
			$('#no_of_days').prop('readonly',true);
		}
	});
	$('input[name="monetize_flag"]').off('change');
	$('input[name="monetize_flag"]').on('change',function(e){
		var selected = $('input[name="monetize_flag"]:checked').val();
		if(selected)
		{
			$('#div_date_range').addClass('none');
			$('#div_commutation').addClass('none'); 
			$('input[name="commutation_flag"]').prop('disabled',true);
			$('#no_of_days').prop('readonly',false);
		}
		else
		{
			$('#div_date_range').removeClass('none');
			$('#div_commutation').removeClass('none');
			$('input[name="commutation_flag"]').prop('disabled',false);
			$('#no_of_days').prop('readonly',true);
		}
	});
	$('input[name="reason"]').off('change');
	$('input[name="reason"]').on('change',function(e){
		var reason = $('input[name="reason"]:checked').val();
		if(reason === "O")
		{
			$('#div_text_reason').removeClass('none');
			$('#div_location').removeClass('none');
			
		}
		else
		{
			$('#div_text_reason').addClass('none');
			$('#div_location').addClass('none');
		}
	});
	$('input[name="location"]').off('change');
	//marvin : start : disabled
	// $('input[name="location"]').on('change',function(e){
		// var location = $('input[name="location"]:checked').val();
		// if(location === "A")
		// {
			// $('#div_text_location').removeClass('none');
		// }
		// else
		// {
			// $('#div_text_location').addClass('none');
		// }
	// });
	//marvin : end : disabled
	$('#form_leave_application').parsley();
	jQuery(document).off('submit', '#form_leave_application');
	jQuery(document).on('submit', '#form_leave_application', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_leave_application').serialize();
		  	button_loader('save_leave_application', 1);
		  	var option = {
					url  : $base_url + 'main/employee_dtr/process_leave_request',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_apply_leave.closeModal();
							var post_data = {
											'employee_id':$('#employee_id').val()
								};
							load_datatable('table_employee_leave_list', '<?php echo PROJECT_MAIN ?>/employee_dtr/get_employee_leave_list',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_leave_application', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
function update_leave_count(that){
		var interval = $(that).val();
		var data ={
			'interval': interval
		}
		if(interval != "")
		{	      	
			$.post($base_url + 'main/employee_dtr/get_interval_value',data, function(result) {
			  
		      	var total_days = 0;
				$(that).closest(".date_row").find('.leave_interval').val(result.interval_value);
				$('.leave_interval').each(function(){
					if($(this).val() > 0)
					{
						total_days = parseFloat(total_days) + parseFloat($(this).val());
					}
				});
				$('#no_of_days').val(total_days);
		     }, 'json'); 
		}
	
}
</script>