<form id="edit_personnel_form">	
	<input type="hidden" name="id" id = "leave_type_id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" name="employee_id" id = "employee_id" value="<?php echo $employee_id ?>"/>	
	<div class="row">
		<div class="col s6">
			<div class="row b-n p-t-lg p-l-md"><span class="font-lg  font-spacing-15"><?php echo isset($benefit_info)? $$benefit_info:""; ?></span></div>
	  	
	<div class="form-float-label">
		<div class="row m-n b-t b-light-gray">
			<div class="col s6">
			  <div class="input-field">
			  	<label for="compensation_id" class="active">Benefit Type<span class="required"> * </span></label>
				<select id="compensation_id" name="compensation_id" class="selectize" disabled>
					 <option value="<?php echo $benefit_info['compensation_id']; ?>"><?php echo $benefit_info['compensation_name']; ?></option>
					</select>
		      </div>
		    </div>
		    <div class="col s6">
				<div class="input-field">
					<input  disabled type="text" class="validate"  name="amount" id="amount" value="<?php echo isset($benefit_info['amount']) ? $benefit_info['amount'] : NULL; ?> " />
					<label class="active" for="amount">Amount<span class="required"> * </span></label>
				</div>
			</div>
		</div>	
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<input id="start_date" name="start_date" class="datepicker_start" type="text" required>
					<label for="start_date" >Start Date <span class="required"> * </span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input id="end_date" name="end_date" class="datepicker_end" type="text">
					<label for="end_date" >End Date </label>
				</div>
			</div>	
		</div>
		</div>
		
		<div class="row b-n p-t-lg p-l-md"><span class="font-md  font-spacing-1">Personnel Category Filter</span></div>
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
			  	<label for="personnel_name" class="active">Personnel Name</label>
				<select id="personnel_name" multiple name="personnel_name[]" class="selectize" placeholder="Select Personnel Name">
					<option value="">Select Personnel Name</option>
				</select>
		      </div>
		    </div>
		</div>
		<div class="row m-n b-b-n b-r-n b-l-n">
			<div class="col s12 b-n">
		     	<button type="button" class="btn btn-success blue pull-right" id="add_to_list" >Add To List</button>
			</div>
		</div>
	</div> 	
			
		
	
		</div>
		<div class="col s6">
	<div class="row b-n p-t-lg p-l-md"><span class="font-lg  font-spacing-15">List of Personnel</span></div>
	 <div class="scroll-pane" style="height:370px;">




		  		<div class="row p-md">
		  			<table class="striped table-default">
		  				<thead>
		  					<tr>
			  					<th width="20%">Personnel Number</th>
			  					<th width="30%">Personnel Name</th>
			  					<th width="10%">Action</th>
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

		$('#adjustment_personnel_list').prepend(adjustment_employee_result_driver);
		adjustment_employee_result_driver = "";
		var personnel_name = $("#adjustment_personnel_name")[0].selectize;
		personnel_name.clear();
		personnel_name.clearOptions();
	});

	$('#office,#position,#salary_grade').off('change');
	$('#office,#position,#salary_grade').on('change', function(e){

		var personnel_name = $("#adjustment_personnel_name")[0].selectize;

		var data = $('#edit_personnel_form').serialize();
		
		personnel_name.disable();
		$.post($base_url + 'main/compensation/get_remove_personnel_list/', data, function(result)
		{
			adjustment_employee_result_driver = result.append_personnnel_list;
			adjustment_general_list = result.append_personnnel_list;

			personnel_name.clear();
			personnel_name.clearOptions();

			personnel_name.load(function(callback) {
				callback(result.list);
				personnel_name.enable();

			});
		 				  
		}, 'json');
		
	});
	$('#adjustment_personnel_name').off('change');
	$('#adjustment_personnel_name').on('change', function(e){

		if($(this).val())
		{
			var data = $('#edit_personnel_form').serialize();
		
			$.post($base_url + 'main/compensation/get_specific_personnel/', data, function(result)
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
			$('.confirmModal_content h4').html('Are you sure you want to remove this personnel from list?');	
			$('.confirmModal_content p').html('This action will remove this perssonel from list.');
		},
		onClose : function() {}
		});
	   	
	});

	$('#edit_personnel_form').parsley();
	jQuery(document).off('submit', '#edit_personnel_form');
	jQuery(document).on('submit', '#edit_personnel_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#edit_personnel_form').serialize();
		  	button_loader('save_adjustment', 1);
		  	var option = {
					url  : $base_url + 'main/compensation/process_edit_personnel_to_benefits',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_adjust_leave.closeModal();
							var post_data = {
											'compensation_id':$('#compensation_id').val()
								};
							load_datatable('table_benefit_employee_list', '<?php echo PROJECT_MAIN ?>/compensation/get_employee_list',false,0,0,true,post_data);
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