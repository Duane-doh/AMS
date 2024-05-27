<form id="sp_payroll_detail_form">

		<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
		<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
		<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
		<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
		<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">

		<div class="form-float-label">
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<label for="office_name" class="active">Office<span class="required">*</span></label>
						<input id="office_name" disabled name="office_name" type="text" class="validate" value="<?php echo $val['office_name'];?>">
					</div>
				</div>			
				<div class="col s6">
					<div class="input-field">
						<label for="employee_name" class="active">Employee<span class="required">*</span></label>
						<input id="employee_name" disabled  name="employee_name" type="text" class="validate" value="<?php echo $val['employee_name'];?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<label for="payout_date" class="active">Position<span class="required">*</span></label>
						<input id="payout_date" disabled name="payout_date" type="text" class="validate" value="<?php echo $val['position_name'];?>">
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<label for="basic_amount" class="active">Basic Amount<span class="required">*</span></label>
						<input id="basic_amount" disabled name="basic_amount" type="text" class="validate" value="<?php echo $val['basic_amount'];?>">
					</div>
				</div>				
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<label for="tenure" class="active">Tenure/Aggregated Tenure<span class="required">*</span></label>
						<input id="tenure" disabled name="payout_date" type="text" class="validate" value="<?php echo $val['tenure_in_months'];?>">
					</div>
				</div>			
				<div class="col s6">
					<div class="input-field">
						<label for="perf_rating" class="active">Performance Rating<span class="required">*</span></label>
						<input id="perf_rating" disabled name="perf_rating" type="text" class="validate" value="<?php echo $val['perf_rating'] . '-' . $val['perf_rating_description'];?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						<label for="base_rate" class="active">Rate<span class="required">*</span></label>
						<input id="base_rate" name="base_rate" type="text" class="validate" value="<?php echo $val['base_rate'];?>">
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<label for="amount" class="active">Amount<span class="required">*</span></label>
						<input id="amount" name="amount" type="text" class="validate" value="<?php echo $val['amount'];?>">
					</div>
				</div>
			</div>
		</div>
		<div class="md-footer default">
			<a class="waves-effect waves-teal btn-flat cancel_modal" id="modal_cancel">Cancel</a>
		  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		    <button id="sp_payroll_report" class="btn btn-success" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		  <?php //endif; ?>
		</div>
</form>
<script>
$(function (){	
	$('#sp_payroll_detail_form').parsley();
	

	$("#sp_payroll_detail_form").on('submit', function (e){
		e.preventDefault();
		if($(this).parsley().isValid())
		{
			var data = $(this).serialize();
			button_loader('sp_payroll_report', 1);
			var option = {
					url  : $base_url + 'main/special_payroll/process_special_payroll',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#modal_cancel").trigger('click');
							load_datatable('table_process_special_payroll_list', '<?php echo PROJECT_MAIN ?>/special_payroll/get_process_special_payroll');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('sp_payroll_report', 0);
					}
			};

			General.ajax(option); 
		}
	});
})

</script>