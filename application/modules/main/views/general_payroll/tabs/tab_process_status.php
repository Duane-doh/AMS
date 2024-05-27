<div class="p-t-lg" id="tab_process_status_content">
<form id="payroll_status_form">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="col s7">
		<div class="form-float-label" style="border-top: solid 1px #D5D5D5">
			<div class="row">
				<div class="col s6">
					<div class="input-field">
						 <label for="payroll_type" class="active">Payroll Type</label>
						 <input disabled type="text" id="payroll_type" value="<?php echo (!EMPTY($val['payroll_type_name']) ? $val['payroll_type_name'] : '') ?>">
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						 <label for="payroll_period" class="active">Attendance Period</label>
						 <input disabled type="text" id="payroll_period" value="<?php echo (!EMPTY($val['payroll_period']) ? $val['payroll_period'] : '') ?>">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<label for="payout_date" class="active">Payout Date</label>
					 	<input disabled id="payout_date" type="text" value="<?php echo (!EMPTY($val['payout_date']) ? $val['payout_date'] : '') ?>" />
					</div>
				</div>
			</div>
		</div>	
	</div>
	<div class="col s5">
		<div class="form-float-label" style="border-top: solid 1px #D5D5D5">
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<label for="payout_status_id" class="active">Status<span class="required">*</span></label>
					 	<select id="payout_status_id" name="payout_status_id" class="selectize" placeholder="Select Status" required>
							<option value=""></option>
							<?php foreach($status as $stat): 
								$selected = ($stat['payout_status_id'] == $val['payout_status_id'] ? ' selected ' : '');
							?>
								<option value="<?php echo $stat['payout_status_id'];?>" <?php echo $selected; ?>>	<?php echo $stat['payout_status_name']; ?></option>
							<?php endforeach; ?>
					 	</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<label for="remarks" class="active">Remarks</label>
					 	<input id="remarks" name="remarks" type="text" value="" />
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col s12 p-r-sm p-t-md">
		<?php if ($permission_status) : ?>
			<button class="btn btn-success right" id="save_change_status" value="<?php echo BTN_PROCESS ?>"><?php echo BTN_PROCESS ?></button>
		<?php endif; ?>
	</div>
</form>
</div>

<script>
$(function (){

	$('#payroll_status_form').parsley();
	$("#payroll_status_form").on('submit', function (e){
		e.preventDefault();
		if($(this).parsley().isValid())
		{
			var data = $(this).serialize();
			button_loader('save_change_status', 1);
			$('#tab_process_status_content').isLoading();
			
			var option = {
					url  : $base_url + 'main/payroll_general_tab/process_status',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_change_status', 0);
						$('#tab_process_status_content').isLoading('hide');
					}
			};

			General.ajax(option); 
		}
	});



})
</script>