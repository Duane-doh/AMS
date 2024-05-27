<form id="leave_adjustment_form">	
	<input type="hidden" name="id" id = "leave_type_id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="employee_id" id = "employee_id" value="<?php echo $employee_id ?>"/>	
	<div class="row">		
		<div class="col s5">
			<div class="scroll-pane" style="height:450px;">
				<div class="row b-n p-t-lg p-l-md"><span class="font-lg font-spacing-15"><?php echo isset($leave_type)? $leave_type:""; ?></span></div>
				<div class="form-float-label">
					<div class="row m-n b-t b-light-gray">
						<div class="col s12">
						  <div class="input-field">
						  	<label for="transaction_type" class="active">Leave Transaction Type <span class="required"> * </span></label>
							<select id="transaction_type" name="transaction_type" class="selectize" placeholder="Select Leave Transaction Type">
								<option value="">Select Leave Transaction Type</option>
								<?php if (!EMPTY($transaction_types)): ?>
									<?php foreach ($transaction_types as $type): ?>
										<option value="<?php echo $type['leave_transaction_type_id'] ?>"><?php echo $type['leave_transaction_type_name'] ?></option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
					      </div>
					    </div>
					</div>	
					<div class="row m-n">
						<div class="col s6">
							<div class="input-field">
								<input id="leave_earned_used" name="leave_earned_used" type="text" required>
								<label for="leave_earned_used" >Number of Days <span class="required"> * </span></label>
							</div>
						</div>
						<div class="col s6">
							<div id="div_effective_date" class="input-field">
								<input id="effective_date" name="effective_date" type="text" required class="datepicker">
								<label for="effective_date" >Effectivity Date <span class="required"> * </span></label>
							</div>
							<div id="div_no_of_days_wop" class="input-field none">
								<input id="no_of_days_wop" name="no_of_days_wop" type="text" required value="0">
								<label for="no_of_days_wop" class="active">Number of Days w/o Pay <span class="required"> * </span></label>
							</div>
						</div>
					</div>	
					<div class="none" id="date_range">
						<div class="form-float-label">
							<div class="row m-n  b-b-n b-t b-light-gray" >
								<div class="col s6">
									<div class="input-field">
										<input id="leave_start_date" name="leave_start_date" class="datepicker_start" type="text" required>
										<label for="leave_start_date" >Leave Start Date <span class="required"> * </span></label>
									</div>
								</div>
								<div class="col s6">
									<div class="input-field">
										<input id="leave_end_date" name="leave_end_date" class="datepicker_end" type="text" required>
										<label for="leave_end_date" >Leave End Date <span class="required"> * </span></label>
									</div>
								</div>
							</div>
						</div>		
					</div>				
					<div class="row m-n">
						<div class="col s12">
							<div class="input-field">
							  	<label for="remarks">Remarks <span class="required"> * </span></label>
			      				<textarea name="remarks" class="materialize-textarea" id="remarks" required></textarea>
							</div>
						 </div>	
					</div>
				</div>

				<div class="row b-n p-t-lg p-l-md"><span class="font-md font-spacing-1">Employee Category Filter</span></div>
				 <div class="form-float-label m-b-xl p-b-xl">
					<div class="row m-n b-t b-light-gray">
						<div class="col s12">
						  <div class="input-field">
						  	<label for="office" class="active">Office</label>
							<select id="office" name="office" class="selectize" placeholder="Select Office">
								<option value="">Select Office</option>
								<?php if (!EMPTY($offices)): ?>
									<?php foreach ($offices as $type): ?>
										<option value="<?php echo $type['office_id'] ?>"><?php echo $type['name'] ?></option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
					      </div>
					    </div>
					</div>
					<div class="row m-n">
						<div class="col s12">
						  <div class="input-field">
						  	<label for="position" class="active">Position</label>
							<select id="position" name="position" class="selectize" placeholder="Select Position">
								<option value="">Select Position</option>
								<?php if (!EMPTY($positions)): ?>
									<?php foreach ($positions as $type): ?>
										<option value="<?php echo $type['position_id'] ?>"><?php echo $type['position_name'] ?></option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
					      </div>
					    </div>
					</div>
					<div class="row m-n">
						<div class="col s12">
						  <div class="input-field">
						  	<label for="salary_grade" class="active">Salary Grade</label>
							<select id="salary_grade" name="salary_grade" class="selectize" placeholder="Select Salary Grade">
								<option value="">Select Salary Grade</option>
								<?php if (!EMPTY($salary_grade)): ?>
									<?php foreach ($salary_grade as $type): ?>
										<option value="<?php echo $type['salary_grade'] ?>"><?php echo $type['salary_grade'] ?></option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
					      </div>
					    </div>
					</div>	
					<div class="row m-n">
						<div class="col s12">
						  <div class="input-field">
						  	<label for="adjustment_personnel_name" class="active">Employee Name</label>
							<select id="adjustment_personnel_name"  name="personnel_name[]" class="selectize" placeholder="Select Employee Name" multiple>
								<option value="">Select Employee Name</option>
							</select>
					      </div>
					    </div>
					</div>
					<div class="row m-n b-b-n b-r-n b-l-n">
						<div class="col s12 b-n">
					     	<button type="button" class="btn btn-success blue pull-right" id="add_to_justment_list" value="Checking For Employees">Add To List</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col s7">			
			<div class="row b-n p-t-lg p-l-md"><span class="font-lg font-playfair-display font-spacing-15">List of Employee</span></div>
		  	<div class="scroll-pane" style="height:370px;">
		  		<div class="row p-md">
		  			<table class="striped table-default">
		  				<thead>
		  					<tr>
			  					<th width="30%">Employee Number</th>
			  					<th width="55%">Employee Name</th>
			  					<th width="15%">Action</th>
		  					</tr>
		  				</thead>
			  			<tbody id="adjustment_personnel_list">
			  			</tbody>
		  			</table>
		  		</div>
	  		</div>
		</div>
	</div>
	
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
	    <button class="btn btn-success  green" id="save_adjustment" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>
<script>
$(document).ready(function(){
	var adjustment_employee_result_driver = "";
	var adjustment_general_list           = "";
	var adjustment_selected_list          = "";

	$('#add_to_justment_list').off('click');
	$('#add_to_justment_list').on('click',function(e){
		if(adjustment_employee_result_driver != "")
		{
			$('#adjustment_personnel_list').prepend(adjustment_employee_result_driver);
			adjustment_employee_result_driver = "";
			var personnel_name = $("#adjustment_personnel_name")[0].selectize;
			personnel_name.clear();
			personnel_name.clearOptions();
		}
		else
		{
			notification_msg("<?php echo ERROR ?>", "No employees available for the selected parameters.");
		}
		
	});

	$('#office,#position,#salary_grade').off('change');
	$('#office,#position,#salary_grade').on('change', function(e){

		var personnel_name = $("#adjustment_personnel_name")[0].selectize;

		var data = $('#leave_adjustment_form').serialize();
		button_loader('add_to_justment_list', 1);
		personnel_name.disable();
		$.post($base_url + 'main/leaves/get_remove_personnel_list/', data, function(result)
		{
			adjustment_employee_result_driver = result.append_personnnel_list;
			adjustment_general_list = result.append_personnnel_list;

			personnel_name.clear();
			personnel_name.clearOptions();

			personnel_name.load(function(callback) {
				callback(result.list);
				personnel_name.enable();
				button_loader('add_to_justment_list', 0);
			});
		 				  
		}, 'json');
		
	});
	$('#adjustment_personnel_name').off('change');
	$('#adjustment_personnel_name').on('change', function(e){
		
		if($(this).val())
		{
			var data = $('#leave_adjustment_form').serialize();
		
			$.post($base_url + 'main/leaves/get_specific_personnel/', data, function(result)
			{
				adjustment_employee_result_driver = result.append_personnnel_list;
				adjustment_selected_list = result.append_personnnel_list;
			 				  
			}, 'json');
		}
		else
		{
			adjustment_employee_result_driver = adjustment_general_list;
		}
		
		
	});

  	jQuery(document).off('click', '#remove_personnel');
	jQuery(document).on('click', '#remove_personnel', function(e)
	{
	   	var btn 	= $(this);
	   	
		$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			btn.closest('.employee_div').remove(); 
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Are you sure you want to remove this perssonel from list?');	
			$('.confirmModal_content p').html('This action will remove this perssonel from list.');
		},
		onClose : function() {}
		});
	   	
	});
	$('#transaction_type').off('change');
	$('#transaction_type').on('change', function(e){
		var type = $(this).val();

		if(type == '<?php echo LEAVE_INITIAL_BALANCE?>' || type == '<?php echo LEAVE_CREDIT_LEAVE?>')
		{
			$("#date_range").addClass('none');
			$('#leave_start_date').attr('required',false);
			$('#leave_end_date').attr('required',false);
		}
		else
		{
			$("#date_range").removeClass('none');
			$('#leave_start_date').attr('required',true);
			$('#leave_end_date').attr('required',true);
		}
		if(type == '<?php echo LEAVE_FILE_LEAVE?>')
		{
			$('#div_no_of_days_wop').removeClass('none');
			$('#div_effective_date').addClass('none');

			$('#no_of_days_wop').attr('required',true);
			$('#effective_date').attr('required',false);
		}
		else
		{
			$('#div_effective_date').removeClass('none');
			$('#div_no_of_days_wop').addClass('none');
			
			$('#no_of_days_wop').attr('required',false);
			$('#effective_date').attr('required',true);
		}
	});
	$('#leave_adjustment_form').parsley();
	jQuery(document).off('submit', '#leave_adjustment_form');
	jQuery(document).on('submit', '#leave_adjustment_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#leave_adjustment_form').serialize();
		  	button_loader('save_adjustment', 1);
		  	var option = {
					url  : $base_url + 'main/leaves/process_leave_adjustment',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_adjust_leave.closeModal();
							var post_data = {
											'leave_type_id':$('#leave_type_id').val()
								};
							load_datatable('table_leave_type_adjustment', '<?php echo PROJECT_MAIN ?>/leaves/get_leave_type_employee_list',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_adjustment', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>