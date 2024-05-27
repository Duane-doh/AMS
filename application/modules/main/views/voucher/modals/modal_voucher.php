
<form id="employee_voucher_form">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>	
		<div class="form-float-label m-b-lg p-b-md">
			<div class="row">
				<div class="col s12">
					<div class="input-field">
			   			<label for="voucher_description">Voucher Description <span class="required"> * </span></label>
			   			<textarea name="voucher_description" class="materialize-textarea" id="voucher_description" required <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>><?php echo isset($voucher_info['voucher_description'])? $voucher_info['voucher_description']:''?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
					  	<label for="employee" class="active">Employee <span class="required"> * </span></label>
						 <select id="employee"  name="employee" class="selectize" placeholder="Select Employee" required <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
							<option value="">Select Employee</option>	
							 <?php if (!EMPTY($employees)): ?>
								<?php foreach ($employees as $employee): ?>
									<option value="<?php echo $employee['employee_id'] ?>"><?php echo $employee['employee_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>		
					 	 </select>
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
					  	<label for="bank_account" class="active">Bank Account</label>
					  	<input type="text" name="bank_account" id="bank_account" disabled="" value="<?php echo isset($voucher_info['bank_account'])? $voucher_info['bank_account']:''?>">
					  	<input type="hidden" name="bank" id="bank" value="<?php echo isset($voucher_info['bank_account'])? $voucher_info['bank_account']:''?>">
					</div>
				</div>
				<div class="col s3">
					<div class="input-field">
					 	<input type="text" class="datepicker" id="voucher_date" name="voucher_date" required value="<?php echo isset($voucher_info['voucher_date'])? $voucher_info['voucher_date']:''?>" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?> onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'voucher_date')"/>
			   			<label for="voucher_date" class="active">Voucher Date <span class="required"> * </span></label>
					</div>
				</div>
			</div>
			<?php if ($action != ACTION_VIEW):?>
			<div class="row">
				<div class="right-align p-l-xl p-b-xs p-r-xs">
				  <div class="input-field inline">
				 		<button type="button"class="btn btn-success" id="add_row_compensation">Add Compensation</button>
				  </div>
				</div>
			</div>
			<?php endif;?>	
			<div class="row">
				<div class="form-basic">
				  <table cellpadding="0" cellspacing="0" class="table table-default" id="table_compensations">
					  <thead>
						<tr>
						  <th width="45%">Compensation</th>
						  <th width="45%">Amount</th>
						  <th width="10%">Action</th>
						</tr>
					  </thead>
					  <tbody id="compensations_tbody">
					  	<?php if($action == ACTION_ADD OR EMPTY($compensation_list)):?>

					  	 <tr id="compensations_tr">
					  	 	<td>
					  	 		<select id="compensation"  name="compensation[]" class="selectize" placeholder="Select Compensation" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
									<option value="">Select Compensation</option>	
									 <?php if (!EMPTY($compensations)): ?>
										<?php foreach ($compensations as $compensation): ?>
											<option value="<?php echo $compensation['compensation_id'] ?>"><?php echo $compensation['compensation_detail'] ?></option>
										<?php endforeach;?>
									<?php endif;?>		
							 	 </select>
					  	 	</td>
					  	 	<td><input type="text" class="number" name="compensation_amount[]" required placeholder="Amount" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>/></td>
					  	 	<td class="table-actions valign-middle <?php echo ($action == ACTION_VIEW) ? 'none':''?>"><a href="javascript:;" class="delete" onclick="delete_compensations_tr(this)"></a></td>
					  	 </tr>
					  	 <?php else: ?>	
						  	 <?php foreach ($compensation_list as $key => $value): ?>
						  	 	 <tr id="compensations_tr">
							  	 	<td>
							  	 		<select id="compensation_<?php echo $key?>"  name="compensation[]" class="selectize" placeholder="Select Compensation" required <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
											<option value="">Select Compensation</option>	
											 <?php if (!EMPTY($compensations)): ?>
												<?php foreach ($compensations as $compensation): ?>
													<?php 
														$selected = "";
														if($compensation['compensation_id'] == $value['compensation_id'])
														{
															$selected = "selected";
														}
														
													?>
													<option value="<?php echo $compensation['compensation_id'] ?>" <?php echo $selected;?>><?php echo $compensation['compensation_detail'] ?></option>
												<?php endforeach;?>
											<?php endif;?>		
									 	 </select>
							  	 	</td>
							  	 	<td><input type="text" class="number" name="compensation_amount[]" required placeholder="Amount" value="<?php echo isset($value['amount']) ? $value['amount'] : ""?>" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>/></td>
							  	 	<td class="table-actions valign-middle <?php echo ($action == ACTION_VIEW) ? 'none':''?>"><a href="javascript:;" class="delete" onclick="delete_compensations_tr(this)"></a></td>
							  	 </tr>
						  	 <?php endforeach;?>
					  	 <?php endif; ?>
					  </tbody>
				  </table>
				</div>
			</div>
			<?php if ($action != ACTION_VIEW):?>
			<div class="row">
				<div class="right-align p-l-xl p-b-xs p-r-xs">
				  <div class="input-field inline">
				 		<button type="button" class="btn btn-success" id="add_row_deduction">Add Deduction</button>
				  </div>
				</div>
			</div>
			<?php endif;?>	
			<div class="row">
				<div class="form-basic">
				  <table cellpadding="0" cellspacing="0" class="table table-default" id="table_deductions">
					  <thead>
						  <th width="45%">Deduction</th>
						  <th width="45%">Amount</th>
						  <th width="10%">Action</th>
					  </thead>
					  <tbody id="deductions_tbody">
					  	<?php if($action == ACTION_ADD OR EMPTY($deduction_list)):?>
					  	 <tr id="deductions_tr">
					  	 	<td>
					  	 		<select id="deduction"  name="deduction[]" class="selectize" placeholder="Select Deduction" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
									<option value="">Select Deduction</option>	
									 <?php if (!EMPTY($deductions)): ?>
										<?php foreach ($deductions as $deduction): ?>
											<option value="<?php echo $deduction['deduction_id'] ?>"><?php echo $deduction['deduction_detail'] ?></option>
										<?php endforeach;?>
									<?php endif;?>		
							 	 </select>
							 </td>
					  	 	<td><input type="text" class="number" name="deduction_amount[]" required placeholder="Amount" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>/></td>
					  	 	<td class="table-actions valign-middle <?php echo ($action == ACTION_VIEW) ? 'none':''?>"><a href="javascript:;" class="delete" onclick="delete_deductions_tr(this)"></a></td>
					  	 </tr>
					  	 <?php else: ?>	
					  	 <?php foreach ($deduction_list as $key => $value): ?>
						  	 <tr id="deductions_tr">
						  	 	<td>
						  	 		<select id="deduction_<?php echo $key?>"  name="deduction[]" class="selectize" placeholder="Select Deduction" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
										<option value="">Select Deduction</option>	
										 <?php if (!EMPTY($deductions)): ?>
											<?php foreach ($deductions as $deduction): ?>
											<?php 
												$selected = "";
												if($deduction['deduction_id'] == $value['deduction_id'])
												{
													$selected = "selected";
												}
											?>
												<option value="<?php echo $deduction['deduction_id'] ?>" <?php echo $selected;?>><?php echo $deduction['deduction_detail'] ?></option>
											<?php endforeach;?>
										<?php endif;?>		
								 	 </select>
								 </td>
						  	 	<td><input type="text" class="number" name="deduction_amount[]" placeholder="Amount" value="<?php echo isset($value['amount']) ? $value['amount'] : ""?>" <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>/></td>
						  	 	<td class="table-actions valign-middle <?php echo ($action == ACTION_VIEW) ? 'none':''?>"><a href="javascript:;" class="delete" onclick="delete_deductions_tr(this)"></a></td>
						  	 </tr>
					  	<?php endforeach;?>
					  	 <?php endif; ?>	
					  </tbody>
				  </table>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<textarea type="text" name="voucher_footer" id="voucher_footer" aria-required="true"  class="materialize-textarea" placeholder="Details here..." <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>><?php echo isset($voucher_info['voucher_footer']) ? $voucher_info['voucher_footer'] : ""?></textarea>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s4">
					<div class="input-field">
					 <label for="certified" class="active">Certified By <span class="required"> * </span></label>
					 <select  name = "certified" id="certified" class="selectize" placeholder="Select Personnel" required <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
						<option value="">Select Personnel</option>
						 <?php if (!EMPTY($certified_by)): ?>
								<?php foreach ($certified_by as $certified): ?>
									<option value="<?php echo $certified['employee_id'] ?>"><?php echo $certified['employee_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>	
				 	 </select>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
					 <label for="ca_certified" class="active">CA Certified By <span class="required"> * </span></label>
					 <select name="ca_certified" id="ca_certified" class="selectize" placeholder="Select Personnel" required <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
						<option value="">Select Personnel</option>
						 <?php if (!EMPTY($ca_certified_by)): ?>
								<?php foreach ($ca_certified_by as $certified): ?>
									<option value="<?php echo $certified['employee_id'] ?>"><?php echo $certified['employee_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
				 	 </select>
					</div>
				</div>
				<div class="col s4">
					<div class="input-field">
					 <label for="approved" class="active">Approved By <span class="required"> * </span></label>
					 <select name="approved" id="approved" class="selectize" placeholder="Select Personnel" required <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>>
						<option value="">Select Personnel</option>
						 <?php if (!EMPTY($approved_by)): ?>
								<?php foreach ($approved_by as $approved): ?>
									<option value="<?php echo $approved['employee_id'] ?>"><?php echo $approved['employee_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
				 	 </select>
					</div>
				</div>
			</div>
		</div>
	<?php if ($action != ACTION_VIEW):?>
	<div class="md-footer default m-t-xl">
		<a class="btn-flat cancel_modal">Cancel</a>
	    <button id="save_voucher" class="btn btn-success ">Save</button>
	</div>
<?php endif;?>
</form>

<script>

var employees = '<?php echo json_encode($employees)?>';

<?php if($action != ACTION_ADD): ?>
		$('.input-field label').addClass('active');
<?php endif; ?>	
var compensation_options = "<option value=''>Select Compensation</option>";
var deduction_options    = "<option value=''>Select Deduction</option>";
 <?php if (!EMPTY($compensations)): 
	foreach ($compensations as $compensation): ?>
		compensation_options += '<option value="' + '<?php echo $compensation["compensation_id"] ?>' +'">';
		compensation_options += '<?php echo $compensation["compensation_detail"] ?>' + '</option>';
	<?php endforeach;
endif;?> <?php if (!EMPTY($deductions)): 
	foreach ($deductions as $deduction): ?>
		deduction_options += '<option value="' + '<?php echo $deduction["deduction_id"] ?>' +'">';
		deduction_options += '<?php echo $deduction["deduction_detail"] ?>' + '</option>';
	<?php endforeach;
endif;?>	
$(function (){
	/*
	 * Initiate Text Editor
	 */
	CKEDITOR.replace('voucher_footer', {
	    removePlugins: 'toolbar'
	});
 	$('input.number').number(true, 2);

	var compensation_count = parseInt(<?php echo isset($compensation_list) ? count($compensation_list) : "0" ?>);
	var deduction_count    = parseInt(<?php echo isset($deduction_list) ? count($deduction_list) : "0" ?>);
	$('#add_row_compensation').off('click');
	$('#add_row_compensation').on('click',function(e){
		e.preventDefault();

		compensation_count++;
		var str = '<tr id="compensations_tr">'+
			  	 	'<td>'+
			  	 		'<select id="compensation_'+compensation_count+'"  name="compensation[]" placeholder="Select Compensation" required>'+
							compensation_options +
					 	 '</select>'+
			  	 	'</td>'+
			  	 	'<td><input type="text" id="compensation_amount_'+compensation_count+'" class="number" name="compensation_amount[]" required placeholder="Amount"/></td>'+
			  	 	'<td class="table-actions valign-middle"><a href="javascript:;" class="delete" onclick="delete_compensations_tr(this)"></a></td>'+
			  	 '</tr>';
		$('#compensations_tbody').append(str);
		$('#compensation_'+compensation_count).selectize();
 		$('#compensation_amount_'+compensation_count).number(true, 2);
	});
	$('#add_row_deduction').off('click');
	$('#add_row_deduction').on('click',function(e){
		e.preventDefault();

		deduction_count++;
		var str = '<tr id="deductions_tr">'+
			  	 	'<td>'+
			  	 		'<select id="deduction_'+deduction_count+'"  name="deduction[]" placeholder="Select Deduction" required>'+
							deduction_options +
					 	 '</select>'+
			  	 	'</td>'+
			  	 	'<td><input type="text" id="deduction_amount_'+deduction_count+'" class="number" name="deduction_amount[]" placeholder="Amount" required/></td>'+
			  	 	'<td class="table-actions valign-middle"><a href="javascript:;" class="delete" onclick="delete_deductions_tr(this)"></a></td>'+
			  	 '</tr>';
		$('#deductions_tbody').append(str);
		$('#deduction_'+deduction_count).selectize();
 		$('#deduction_amount_'+deduction_count).number(true, 2);
	});
})
function delete_compensations_tr(that){
		$(that).closest('#compensations_tr').remove();
}
function delete_deductions_tr(that){
		$(that).closest('#deductions_tr').remove();
}
$(document).ready(function(){
	$('#employee_voucher_form').parsley();
	jQuery(document).off('submit', '#employee_voucher_form');
	jQuery(document).on('submit', '#employee_voucher_form', function(e){
	    e.preventDefault();

	    $('#voucher_footer').val(CKEDITOR.instances['voucher_footer'].getData());
		if ( $(this).parsley().isValid() ) {
			var data = $('#employee_voucher_form').serialize();
		  	button_loader('save_voucher', 1);
		  	var option = {
					url  : $base_url + 'main/payroll_voucher/process_voucher',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_voucher.closeModal();
							
							load_datatable('payroll_list', '<?php echo PROJECT_MAIN ?>/payroll_voucher/get_payroll_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_voucher', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
	});

$('#employee').change(function(){
	var employee_id = $(this).val();
	var option = {
	url  : $base_url + 'main/payroll_voucher/get_bank_account',
	data : {employee_id, employee_id},
	success : function(result){
		if(result.status)
		{
			$('#bank_account').val(result.bank_account);
			$('#bank').val(result.bank_account);
		}
		else
		{
			notification_msg("<?php echo ERROR ?>", result.message);
			$('#bank_account').val(result.bank_account);
			$('#bank').val(result.bank_account);
		}	
		
	}
};

General.ajax(option); 
	
});
</script>
	