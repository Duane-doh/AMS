<?php

	$r_flag 	= $gsis_info[0];
	$efc_flag 	= end($gsis_info);

?>

<form id="gsis_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate datepicker" required name="effective_date" id="effective_date" 
				   		   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'effective_date')"
						   value="<?php echo isset($r_flag['effective_date']) ? format_date($r_flag['effective_date']) : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
			   		<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="effective_date">Effectivity Date<span class="required">*</span></label>
				</div>
			</div>
			<div class='col s6 switch p-t-lg'>
		    	<label>
		        	Inactive
			        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($r_flag['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
			        <span class='lever'></span>Active
		    	</label>
			</div>
		</div>
		<div class="row m-r-md m-l-md p-t-md">
			<div class="form-basic">
				<table class="table-default striped">
					<thead class="teal white-text">
						<tr>
							<td width="150px" style="font-size: 12px;"><b>TYPE OF INSURANCE<br>COVERAGE</b></td>
							<td width="150px" style="font-size: 12px;"><b>PERSONAL SHARE</b></td>
							<td width="150px" style="font-size: 12px;"><b>GOVERNMENT SHARE</b></td>
							<td width="150px" style="font-size: 12px;"><b>MAXIMUM GOVERNMENT<br>SHARE</b></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Regular<input name="insurance_type_flag[]" type="hidden" class="validate" required value="<?php echo GSIS_REG?>"></input></td>
							<td><input name="personal_share[]" type="text" class="validate number right-align" required min="0" value="<?php echo isset($r_flag['personal_share']) ? $r_flag['personal_share']* 100 : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
							<td><input name="government_share[]" type="text" class="validate number right-align" required min="0" value="<?php echo isset($r_flag['government_share']) ? $r_flag['government_share']* 100 : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
							<td><input name="max_government_share[]" type="text" class="validate number right-align" required min="0" value="<?php echo isset($r_flag['max_government_share']) ? $r_flag['max_government_share'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
						</tr>
						<tr>
							<td>Employees<br>Compensation Fund<input name="insurance_type_flag[]" type="hidden" class="validate" required value="<?php echo GSIS_ECF?>"></td>
							<td><input name="personal_share[]" type="text" class="validate number right-align" required min="0" value="<?php echo isset($efc_flag['personal_share']) ? $efc_flag['personal_share']* 100 : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
							<td><input name="government_share[]" type="text" class="validate number right-align" required min="0" value="<?php echo isset($efc_flag['government_share']) ? $efc_flag['government_share']* 100 : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
							<td><input name="max_government_share[]" type="text" class="validate number right-align" required min="0" value="<?php echo isset($efc_flag['max_government_share']) ? $efc_flag['max_government_share'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>></input></td>
						</tr>
					</tbody>
				</table>
			</div>	
		</div>
	</div>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_gsis">Cancel</a>
		    <button class="btn btn-success " id="save_gsis" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){
	$('#gsis_form').parsley();
	$('#gsis_form').submit(function(e) {
	    e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_gsis', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_payroll/gsis/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_gsis.closeModal();
							load_datatable('gsis_table', '<?php echo PROJECT_MAIN ?>/code_library_payroll/gsis/get_gsis_list', false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_gsis', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  
  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})
</script>