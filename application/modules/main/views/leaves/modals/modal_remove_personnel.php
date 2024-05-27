<form id="leave_remove_personnel_form">	
	<input type="hidden" name="id" id = "leave_type_id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="row">		
		<div class="col s5">
			<div class="row b-n p-t-lg p-l-md"><span class="font-lg font-spacing-15"><?php echo isset($leave_type)? $leave_type:""; ?></span></div>
			 <div class="form-float-label">
				<div class="row m-n b-t b-light-gray">
					<div class="col s12">
					  <div class="input-field">
					  	<label for="office" class="active">Office</label>
						<select id="office" name="office" class="selectize" placeholder="Select Office">
							<option value="">Select Offices</option>
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
					  	<label for="remove_personnel_name" class="active">Employee Name</label>
						<select id="remove_personnel_name" multiple name="personnel_name[]" class="selectize" placeholder="Select Employee Name">
							<option value="">Select Employee Name</option>
						</select>
				      </div>
				    </div>
				</div>
				<div class="row m-n b-b-n b-r-n b-l-n">
					<div class="col s12 b-n">
				     	<button type="button" class="btn btn-success blue pull-right" id="add_to_remove_list" value="Checking For Employees">Add To List</button>
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
			  			<tbody id="remove_personnel_added_list" >
			  			
			  			</tbody>
		  			</table>
		  		</div>
	  		</div>
		</div>
	</div>
	
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
    <button class="btn btn-success  green" id="save_personnel" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
</form>
<script>
$(document).ready(function(){
	var remove_employee_result_driver = "";
	var remove_general_list           = "";
	var remove_selected_list          = "";

	$('#add_to_remove_list').off('click');
	$('#add_to_remove_list').on('click',function(e){
		if(remove_employee_result_driver != "")
		{

			$('#remove_personnel_added_list').prepend(remove_employee_result_driver);
			remove_employee_result_driver = "";
			var personnel_name = $("#remove_personnel_name")[0].selectize;
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

		var personnel_name = $("#remove_personnel_name")[0].selectize;

		var data = $('#leave_remove_personnel_form').serialize();
		button_loader('add_to_remove_list', 1);
		personnel_name.disable();
		$.post($base_url + 'main/leaves/get_remove_personnel_list/', data, function(result)
		{
			remove_employee_result_driver = result.append_personnnel_list;
			remove_general_list = result.append_personnnel_list;

			personnel_name.clear();
			personnel_name.clearOptions();

			personnel_name.load(function(callback) {
				callback(result.list);
				personnel_name.enable();
				button_loader('add_to_remove_list', 0);
			});
		 				  
		}, 'json');
		
	});
	$('#remove_personnel_name').off('change');
	$('#remove_personnel_name').on('change', function(e){

		if($(this).val())
		{
			var data = $('#leave_remove_personnel_form').serialize();
		
			$.post($base_url + 'main/leaves/get_specific_personnel/', data, function(result)
			{
				remove_employee_result_driver = result.append_personnnel_list;
				remove_selected_list = result.append_personnnel_list;
			 				  
			}, 'json');
		}
		else
		{
			remove_employee_result_driver = remove_general_list;
		}
		
		
	});
	$('#leave_remove_personnel_form').parsley();
	
 	jQuery(document).off('submit', '#leave_remove_personnel_form');
	jQuery(document).on('submit', '#leave_remove_personnel_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#leave_remove_personnel_form').serialize();
		  	button_loader('save_personnel', 1);
		  	var option = {
					url  : $base_url + 'main/leaves/process_remove_personnel',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_remove_personnel.closeModal();
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
						button_loader('save_personnel', 0);
					}
			};

			General.ajax(option);    
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
});
</script>