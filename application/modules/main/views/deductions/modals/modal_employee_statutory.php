
<form id="form_statutory_deductions" class="m-b-md">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<input type="hidden" name="has_permission" id="has_permission" value="<?php echo !EMPTY($has_permission) OR $action == ACTION_ADD ? 1 : NULL?>">
	<input type="hidden" name="active_flag" id="active_flag" value="">
	
	<div class="form-float-label p-b-lg" id="other_deductions_div">
		<div class="row">
		  	<div class="col s12">
				<div class="input-field">
					<label class="active" for="deduction_id">Deduction Type<span class="required">*</span></label>
					<select required="" id="deduction_id" name="deduction_id" class="selectize" placeholder="Select Deduction" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Deductions</option>
					 <?php if (!EMPTY($deduction_types)): ?>
						<?php foreach ($deduction_types as $deduction): ?>
							<option value="<?php echo $deduction['deduction_id'] ?>"><?php echo $deduction['deduction_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
		  	<div class="col s12">
				<div class="input-field">
					<label for="start_date" class="active">Start Date<span class="required">&nbsp;*</span></label>
					<input required="" id="start_date" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'start_date')" name="start_date" type="text" class="validate datepicker" value="<?php echo isset($deduction_info['start_date']) ? format_date($deduction_info['start_date']) : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="form-float-label p-b-lg">
		<div class="row p-b-md">
			<div class="col s12">
				<span class="font-lg font-spacing-12">List of Beneficiaries</span>
			</div>
		</div>
		<div class="row">
		  	<div class="col s12 p-n">
				<div class="pre-datatable filter-left"></div>
				<div>
					<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_personnel_beneficiary_list">
						<thead>
							<tr>
								<th width="50%">Name</th>
								<th width="30%">Birthday</th>
								<th width="10%">Status</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW) : ?>
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel"> Cancel</a>
	   	<button class="btn" id="save_statutory_deduction" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php endif;?>
	</div>
</form>


<script>
var action = <?php echo $action ?>;
$(function (){
	var identification_field_id = "";
	
	$('#form_statutory_deductions').parsley();
	$('#form_statutory_deductions').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_statutory_deduction', 1);
		  	var option = {
					url  : $base_url + '<?php echo PROJECT_MAIN ?>/deductions/process_statutory_deductions',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_employee_statutory.closeModal();

							var employee_id = $('#employee_id').val();
							var module      = $('#module').val();
							var action      = $('#action').val();
							var has_permission      = $('#has_permission').val();
							var post_data   = {'employee_id' : employee_id, 'module' : module, 'action_id' : action, 'has_permission' : has_permission};

							load_datatable('table_statutory_details', '<?php echo PROJECT_MAIN ?>/deductions/get_statutory_details',false,0,0,true,post_data);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_statutory_deduction', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

	var deduction_name               = $('#deduction_id').selectize();
	var deduction_types_details      = <?php echo json_encode($deduction_details) ?>;
	var deduction_types              = <?php echo json_encode($deduction_types) ?>;
	var deduction_other_details_info = <?php echo json_encode($deduction_other_details_info) ?>;

  deduction_name.selectize().on('change', function() {
	
	$('.add_fields').remove();

	
	var deduction_id = deduction_name[0].selectize.getValue();
	var current = '';
	// var val_arr = value.split('|');
	if(deduction_id == <?php echo DEDUC_BIR ?>) flag = '<?php echo BIR_FLAG ?>';
	else if(deduction_id == <?php echo DEDUC_GSIS ?>) flag = '<?php echo GSIS_FLAG ?>';
	else if(deduction_id == <?php echo DEDUC_PAGIBIG ?>) flag = '<?php echo PAGIBIG_FLAG ?>';
	else if(deduction_id == <?php echo DEDUC_PHILHEALTH ?>) flag = '<?php echo PHEALTH_FLAG ?>';
	else if(deduction_id == <?php echo DEDUC_BIR_EWT ?>) flag = '<?php echo BIR_FLAG ?>';
	else if(deduction_id == <?php echo DEDUC_BIR_VAT ?>) flag = '<?php echo BIR_FLAG ?>'; 
	else if(deduction_id == <?php echo DEDUC_PHIC_QTR ?>) flag = '<?php echo PHEALTH_FLAG ?>'; 


	
	var employee_id = $('#employee_id').val();
	// var flag      	= val_arr[1];
	var disabled    = action == 5 ? true : false;
	var disabled_str= (disabled) ? "disabled":"";
	var post_data   = {'employee_id' : employee_id, 'flag' : flag, 'disabled' : disabled, 'disabled_str':disabled_str};
	load_datatable('table_personnel_beneficiary_list', '<?php echo PROJECT_MAIN ?>/deductions/get_personnel_dependents_list',false,0,0,true,post_data);
	$('#table_personnel_beneficiary_list').DataTable().destroy();
	$('#active_flag').val(flag);

	// ADDING FOR OTHER DEDUCTIONS DETAILS
	// START
	// var html = '<div class="row add_fields" id="add_fields">';
	var html = '';
	for(var i=0; i < deduction_types_details.length; i++)
	{
		value = '';
		if(deduction_types_details[i]['deduction_id'] == deduction_id) {
			if(deduction_types_details[i]['other_detail_type'] == 'YN')
			{
				var checked = '';
				if(deduction_other_details_info != null)
				{
					for(var j=0;j<deduction_other_details_info.length;j++)
					{
						if(deduction_other_details_info[j]['other_deduction_detail_id'] == deduction_types_details[i]['other_deduction_detail_id'])
						{
							checked = 'checked';
						}
					}
				}
				html += '<div class="row add_fields"><div class="col s12 additional_fields">'
					 +  	'<div class="switch p-md">'
					 +			 deduction_types_details[i]['other_detail_name'] + '<br><br>'
					 +   		'<label>'
					 +	    	'No'
					 +	        '<input class="switch_me" name="other_deduction_details_switch[]" type="checkbox" ' + checked + (action == 5 ? ' disabled' : '') + ' >' 
					 +	        '<span class="lever"></span>'
					 +	        'Yes'
					 +    		'</label>'
					 +		'</div>'
					 +      '<input value="' + deduction_types_details[i]['other_detail_name'] + '" name="other_deduction_details_switch[]" type="hidden"' + disabled_str + '>'
					 +      '<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details_switch[]" type="hidden"' + disabled_str + '>'
					 +	'</div></div>';
			}

			//DISPLAY DROPDOWN
			if(deduction_types_details[i]['other_detail_type'] == 'DR')
			{
				var options = '';
				if(deduction_types_details[i]['dropdown_flag'] == 'ADDR')
				{
					var addresses = <?php echo json_encode($employee_addresses) ?>;
					if(deduction_other_details_info != null)
					{
						for(var j=0;j<deduction_other_details_info.length;j++)
						{
							if(deduction_other_details_info[j]['other_deduction_detail_id'] == deduction_types_details[i]['other_deduction_detail_id'])
							{
								value = deduction_other_details_info[j]['other_deduction_detail_value'];
							}
						}
					}
					for(var j=0; j<addresses.length; j++)
					{
						var selected = '';
						if(value == addresses[j]['address_value']) selected = 'selected';
						options += '<option value="' + addresses[j]['address_value'] + '" '+selected+'>' + addresses[j]['address_value'] + '</option>';
					}
				}
				html += '<div class="row add_fields"><div class="col s12 additional_fields">'
			    	 +		'<div class="input-field">'
			    	 +			'<label for="' + deduction_types_details[i]['other_deduction_detail_id'] + '" class="active">'
			    	 + 				deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">*</span>' : '')
			    	 +			'</label>'
			    	 +			'<select id="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" placeholder="Select Address" class="selectize" '  + (deduction_types_details[i]['required_flag'] == 'Y' ? 'required' : '') + disabled_str + '>'
			    	 +				options
			    	 +			'</select>'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden"' + disabled_str + '>'
			   		 +		'</div>'
			      	 +	'</div></div>';

			}

			//DISPLAY CHARACTER
			if(deduction_types_details[i]['other_detail_type'] == 'C')
			{ 	
				if(deduction_other_details_info != null)
				{
					for(var j=0;j<deduction_other_details_info.length;j++)
					{
						if(deduction_other_details_info[j]['other_deduction_detail_id'] == deduction_types_details[i]['other_deduction_detail_id'])
						{
							value = deduction_other_details_info[j]['other_deduction_detail_value'];
						}
					}
				}
				html +=	'<div class="row add_fields"><div class="col s12 additional_fields">'
					 +    	'<div class="input-field">'
					 +       	'<label for="' + deduction_types_details[i]['other_deduction_detail_id'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">*</span>' : '') + '</label>'
					 +       	'<input class="validate" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' type="text" name="other_deduction_details[]" id="' + deduction_types_details[i]['other_deduction_detail_id'] + '" value="'+value+'"' + disabled_str + '>'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden"' + disabled_str + '>'
					 +      '</div>'
					 +	'</div></div>';
				identification_field_id = deduction_types_details[i]['other_deduction_detail_id'];
			}

			//DISPLAY NUMBER
			if(deduction_types_details[i]['other_detail_type'] == 'N')
			{
				if(deduction_other_details_info != null)
				{
					for(var j=0;j<deduction_other_details_info.length;j++)
					{
						if(deduction_other_details_info[j]['other_deduction_detail_id'] == deduction_types_details[i]['other_deduction_detail_id'])
						{
							value = deduction_other_details_info[j]['other_deduction_detail_value'];
						}
					}
				}
				html +=	'<div class="row add_fields"><div class="col s12 additional_fields">'
					 +    	'<div class="input-field">'
					 +       	'<input class="validate number" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' type="text" name="other_deduction_details[]" id="' + deduction_types_details[i]['other_deduction_detail_id'] + '" value="'+value+'"' + disabled_str + '>'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden"' + disabled_str + '>'
					 +       	'<label for="' + deduction_types_details[i]['other_deduction_detail_id'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] +  (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">*</span>' : '') + '</label>'
					 +     '</div>'
					 + 	'</div></div>';
			} 

			//DISPLAY DATE
			if(deduction_types_details[i]['other_detail_type'] == 'D')
			{
				if(deduction_other_details_info != null)
				{
					for(var j=0;j<deduction_other_details_info.length;j++)
					{
						if(deduction_other_details_info[j]['other_deduction_detail_id'] == deduction_types_details[i]['other_deduction_detail_id'])
						{
							value = deduction_other_details_info[j]['other_deduction_detail_value'];
						}
					}
				}
				html +=	'<div class="row add_fields"><div class="col s12 additional_fields">'
					 +  	'<div class="input-field">'
					 +      	'<label for="' + deduction_types_details[i]['other_deduction_detail_id'] + '" class="active">' + deduction_types_details[i]['other_detail_name'] + (deduction_types_details[i]['required_flag'] == 'Y' ? '<span class="required">*</span>' : '') + '</label>'
					 +      	'<input class="validate datepicker" ' + (deduction_types_details[i]['required_flag'] == 'Y' ? ' required="" ' : '') + ' id="' + deduction_types_details[i]['other_detail_name'] + '" name="other_deduction_details[]" type="text" value="'+value+'"' + disabled_str + '>'
					 +      	'<input value="' + deduction_types_details[i]['other_deduction_detail_id'] + '" name="other_deduction_details[]" type="hidden"' + disabled_str + '>'
					 +   	'</div>'
					 + 	'</div></div>';
			}
		}
	}
	$('#other_deductions_div').append(html);
	$('select.selectize').selectize();
	// END
	$('input.number').number(true, 2);
	$('.datepicker').datetimepicker('destroy');
	$('.datepicker').datetimepicker({
		timepicker:false,
		format:'Y/m/d'
	});
});

$format = "";

$("#deduction_id").on("change", function(){
   	var $val   	= $(this).val();
   	var emp_id  = $('#employee_id').val();
	var params = [];

	if(identification_field_id == 6)
	{
		identification_field_id = 1;
	}
	params     = {select_id : $val, id : emp_id};
	var option = {
			url  : $base_url + "<?php echo PROJECT_MAIN."/deductions/get_identification_number"?>",
			data : params,
			async : false,
			success : function(result){
				if(result.flag == "1"){	
					$format	= result.format;	
					$('#'+identification_field_id).val(result.id);
				} else {
					//notification_msg("<?php echo ERROR ?>", result.msg);
				}
			},
	};

	General.ajax(option); 


	
});
});
$('#form_statutory_deductions').on('keypress', '#1', function (event) {
	format_identifications($format,this.value,event,'1');
});
<?php if($action == ACTION_VIEW){ ?>
		
		$('label .required').addClass('none');

<?php } ?>
</script>