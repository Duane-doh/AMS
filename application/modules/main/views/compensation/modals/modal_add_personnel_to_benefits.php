<form id="add_personnel_to_benefits_form">	
	<input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" id="action_id" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<input type="hidden" id="selected_counter_input" value="0">
	<div class="row">
		<div class="col s6">
			<div class="row b-n p-t-sm p-l-md"><span class="font-lg font-spacing-15"><?php echo isset($benefit_info)? $$benefit_info:""; ?></span></div>
	  	
			<div class="form-float-label">
				<div class="row m-n b-t b-light-gray">
					<div class="col s12">
						<div class="input-field">
						  	<label for="compensation_id" class="active">Benefit Name</label>
							<input  disabled type="text" id="compensation_id" class="validate" value="<?php echo isset($benefit_info['compensation_name']) ? $benefit_info['compensation_name'] : NULL; ?> " />
					    </div>
				    </div>
				</div>	
				<?php if($action != ACTION_DELETE) : ?>
				<div class="row m-n">
					<div class="col s6">
						<div class="input-field">
							<input id="start_date" name="start_date" class="datepicker_start" type="text" required>
							<label for="start_date">Start Date <span class="required"> * </span></label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
							<input id="end_date" name="end_date" class="datepicker_end" type="text">
							<label for="end_date">End Date </label>
						</div>
					</div>	
				</div>
				<?php endif; ?>
			</div>
			
			<div class="row b-n p-t-lg"><span class="font-md font-spacing-1">Employee Category Filter</span></div>
			<div class="form-float-label">
				<div class="row m-n b-t b-light-gray">
					<div class="col s12">
					  <div class="input-field">
					  	<label for="office" class="active">Office</label>
						<select id="office" class="selectize" placeholder="Select Office">
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
					<div class="col s6">
					  <div class="input-field">
					  	<label for="position" class="active">Position</label>
						<select id="position" class="selectize" placeholder="Select Position">
							<option value="">Select Position</option>
							<?php if (!EMPTY($positions)): ?>
								<?php foreach ($positions as $type): ?>
									<option value="<?php echo $type['position_id'] ?>"><?php echo $type['position_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
				      </div>
				    </div>
					<div class="col s6">
					  <div class="input-field">
					  	<label for="salary_grade" class="active">Salary Grade</label>
						<select id="salary_grade"class="selectize" placeholder="Select Salary Grade">
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
					<div class="col s6">
					  <div class="input-field">
					  	<label for="designation" class="active">Designation</label>
						<select id="designation" class="selectize" placeholder="Select Designation">
							<option></option>
							<?php if (!EMPTY($designation)): ?>
								<?php foreach ($designation as $type): ?>
									<option value="<?php echo $type['others_value'] ?>"><?php echo $type['others_value'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
				      </div>
				    </div>
					<div class="col s6">
					  <div class="input-field">
						<label for="rating" class="active">Performance Rating</label>
						<select id="rating" class="selectize" placeholder="Select Performance Rating" multiple="">
							<option></option>
								<?php foreach($performance_ratings AS $pr){ ?>
									<option value="<?php echo $pr['rating_id'] ?>"><?php echo $pr['rating_description'] ?></option>
								<?php }?>
						</select>
				      </div>
				    </div>
				</div>	
				<div class="row m-n">
					<div class='col s6'>
					  <div class="input-field">
					  	<label for="employ_type_flag" class="active">Employee Type</label>
					  	<select id="employ_type_flag" class="selectize" placeholder="Select Employee Type">
					  		<option value="">Select Employee Type</option>
					  		<?php foreach($employ_type_flag as $type): ?>
									<option value="<?php echo $type['employment_type_code'];?>"><?php echo $type['employment_type_name'];?></option>
							<?php endforeach;?>
						</select>
					  </div>
					</div>
					<div class='col s6'>
					  <div class="input-field">
					  	<label for="coop_member" class="active">Union Member</label>
					  	<select id="coop_member" class="selectize" placeholder="Select Option">
					  		<option value="">Select Option</option>
							<option value="<?php echo YES;?>">Yes</option>
							<option value="<?php echo NO;?>">No</option>
						</select>
					  </div>
					</div>
				</div>
				<div class="row m-n">
					<div class="col s12">
					  <div class="input-field">
					  	<label for="employee" class="active">Employee Name</label>
						<select id="employee" multiple class="selectize" placeholder="Select Employee Name">
							<option value="">Select Employee Name</option>
							<?php foreach($employee_list as $key => $emp): ?>
									<option value="<?php echo $emp['employee_id'] ?>"><?php echo ucwords($emp['fullname']) ?></option>
							<?php endforeach;?>
						</select>
				      </div>
				    </div>
				</div>
				<div class="row m-n b-b-n b-r-n b-l-n">
					<div class="col s12 b-n p-r-n">
				     	<button type="button" class="btn btn-success blue pull-right" id="add_to_list" >Add To List</button>
					</div>
				</div>
			</div> 	
		</div>
		<div class="col s6 p-r-lg">
			<div class="row b-n p-t-lg p-l-md m-b-n"><span class="font-lg font-spacing-15">List of Employees <?php echo $action == ACTION_DELETE ? 'to Remove ' : '' ?></span><label>( <b id="selected_counter">0</b>  selected )</label></div>
	  		<div class="row p-md modal_scroll jspScrollable m-b-n" style="height:350px !important;">
	  			<table class="striped table-default">
	  				<thead>
	  					<tr>
		  					<th width="30%">Employee Number</th>
		  					<th width="60%">Employee Name</th>
		  					<th width="15%">Action</th>
	  					</tr>
	  				</thead>
		  			<tbody id="add_personnel_added_list" >
		  			</tbody>
	  			</table>
	  		</div>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
	    <button class="btn btn-success green none" id="save_adjustment" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
</form>

<script>

var filtered_employees = new Array();
var performance_ratings = <?php echo json_encode($performance_ratings)?>;
$(function(){
	var employee_result_driver = "";
	var general_list           = "";
	var selected_list          = "";
	var employee_list          = <?php echo json_encode($employee_list) ?>;
	var designation            = <?php echo json_encode($designation) ?>;

	$('#add_to_list').off('click');
	

	$('#office,#position,#salary_grade,#rating,#designation,#employ_type_flag,#coop_member').off('change');
	$('#office,#position,#salary_grade,#rating,#designation,#employ_type_flag,#coop_member').on('change', function(e){


		var rating = $('#rating').val();
		if(rating != null) {
			for(var p in rating) 
				for(var pr in performance_ratings) if(rating[p] == performance_ratings[pr]['rating_id']) rating[p] = performance_ratings[pr];
		}

		var personnel_name 		= $("#employee")[0].selectize.destroy();
		var office        		= $('#office').val();
		var position       		= $('#position').val();
		var salary_grade   		= $('#salary_grade').val();
		var designation    		= $('#designation').val();
		var coop_member    		= $('#coop_member').val();
		var employ_type_flag	= $('#employ_type_flag').val();
		var option         		= '<option></option>';
		
		for(var i=0; i<employee_list.length; i++)
		{
			if((office == '' || office == employee_list[i]['employ_office_id']) && (position == '' || position == employee_list[i]['employ_position_id']) && (salary_grade == '' || salary_grade == employee_list[i]['employ_salary_grade']) && (designation == '' || designation == employee_list[i]['designation']) && (employ_type_flag == '' || employ_type_flag == employee_list[i]['employ_type_flag']) && (coop_member == '' || coop_member == employee_list[i]['union_member']))
			{
				if(rating != null) {
					if(employee_list[i]['rating'] == null) continue;
					var included = false;
					for(var r in rating) {

						if(+employee_list[i]['rating'] >= +rating[r]['rating_min_value'] && +employee_list[i]['rating'] <= +rating[r]['rating_max_value']) {
							included = true;
							break;
						}
					}
					if(included == false) continue;
				}
				option += '<option value="' + employee_list[i]['employee_id'] + '">' + employee_list[i]['fullname'] + '</option>';
			}
		}
		$('#employee').html(option).selectize();
		
	});

	$('#add_personnel_to_benefits_form').parsley();
	
 	jQuery(document).off('submit', '#add_personnel_to_benefits_form');
	jQuery(document).on('submit', '#add_personnel_to_benefits_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#add_personnel_to_benefits_form').serialize();
		  	button_loader('save_adjustment', 1);
		  	var option = {
					url  : $base_url + 'main/compensation/process_add_personnel_to_benefits/',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_add_personnel_to_benefits.closeModal();
							var post_data = {
								'compensation_id' : $('#id').val()
							};
							load_datatable('table_benefit_employee_list', '<?php echo PROJECT_MAIN ?>/compensation/get_benefit_employee_list',false,0,0,true,post_data);

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


$('#add_to_list').on('click',function(e){
	
	var office         		= $('#office').val();
	var position        	= $('#position').val();
	var salary_grade    	= $('#salary_grade').val();
	var designation     	= $('#designation').val();
	var rating   			= $('#rating').val();
	var employ_type_flag	= $('#employ_type_flag').val();
	var coop_member 		= $('#coop_member').val();
	var compensation_id 	= $('#id').val();
	var employee 	    	= $('#employee').val();
	var action 	   	   		= $('#action_id').val();

	var data  = 'action='+action;
	    data += '&office='+office;
	    data += '&position='+position;
	    data += '&salary_grade='+salary_grade;
	    data += '&designation='+designation;
	    data += '&rating='+(rating==null ? '' : rating);
	    data += '&employ_type_flag='+employ_type_flag;
	    data += '&coop_member='+coop_member;
	    data += '&compensation_id='+compensation_id;
	    data += '&employee='+(employee==null ? '' : employee);
	    data += '&employee_list='+filtered_employees;

	$.post($base_url + 'main/compensation/get_personnel_list/', data, function(result)
	{
		if(result.status)
		{
			var employees      = result.employee_list;
			var option         = '';
			if(employees.length) {
				
				var total_count = $('#selected_counter_input').val();
				total_count     = parseInt(total_count);

				$('#selected_counter').html(total_count + result.counter);
				$('#selected_counter_input').val(total_count + result.counter);
				var index = filtered_employees.length;
				for(var i=0; i<employees.length; i++) {
					option += '<tr id="table_row_'+employees[i]['employee_id']+'">'
							+ '<td class="p-t-n p-b-n">' + employees[i]['agency_employee_id'] + '</td>'
							+ '<td class="p-t-n p-b-n">' + employees[i]['fullname'] + '</td>'
							+ '<td class="p-t-n p-b-n"><div class="table-actions p-t-sm"><a id="remove_table" href="javascript:;" onclick="remove_table('+employees[i]['employee_id']+')" class="delete tooltipped" data-tooltip="Delete" data-position="bottom" data-delay="50"></a></div></td>'
							+ '<input type="hidden" class="validate listahan" name="employee_list[]" value="' + employees[i]['employee_id'] + '" />'
							+ '</tr>';
					filtered_employees[index++] = employees[i]['employee_id'];
				}
				$('#save_adjustment').removeClass('none');
			}
			else {
				if(filtered_employees.length < 1)
				$('#save_adjustment').addClass('none');
				option = '<tr id="table_row_'+i+'" style="background: #F2c049"><td class="p-t-n p-b-n center" colspan="3">No matching records found</td></tr>';

				setTimeout(function(){ 
					$("#table_row_" + i).remove();
				}, 3000);
			}
			$('#add_personnel_added_list').append(option);
		}
	}, 'json');

});

function remove_table(row_index)
{
	$("#table_row_" + row_index).remove();

	var total_count = $('#selected_counter_input').val();
	total_count     = parseInt(total_count);

	$('#selected_counter').html(total_count - 1);
	$('#selected_counter_input').val(total_count - 1);

	// REMOVE DATA FROM THE CURRENT SELECTED EMPLOYEES
	var index = filtered_employees.indexOf(row_index.toString());
	filtered_employees.splice(index,1);
	if(filtered_employees.length < 1) {
		$('#save_adjustment').addClass('none');
	}
}

</script>