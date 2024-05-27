<form id="remittance_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
				 <label for="remittance_type_id" class="active">Remittance Type<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				 <select id="remittance_type_id" name="remittance_type_id" <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?> required class="selectize validate" placeholder="Select Remittance...">
					<option></option>
					<?php
						foreach($remittance_types AS $remittance)
						{
							echo '<option value="' . $remittance['remittance_type_id'] . '">' . $remittance['remittance_type_name'] . '</option>';
						}

					?>				
			 	 </select>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				 	<input class="validate datepicker_start" name="deduction_start_date" <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?> required id="deduction_start_date" type="text" value="<?php echo (!EMPTY($remittance_info['deduction_start_date']) ? format_date($remittance_info['deduction_start_date']) : '') ?>" />
		   			<label for="deduction_start_date">Deduction Start<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				 	<input class="validate datepicker_end" name="deduction_end_date" <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?> required id="deduction_end_date" type="text" value="<?php echo (!EMPTY($remittance_info['deduction_end_date']) ? format_date($remittance_info['deduction_end_date']) : '') ?>" />
		   			<label for="deduction_end_date">Deduction End<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<?php 
					$year_select = NULL;
					$disabled = FALSE;
					if($remittance_info['year_month'])
					{
						$year_select = substr($remittance_info['year_month'], 0,4);
					}
					if($action == ACTION_VIEW OR $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ) $disabled = TRUE;

				 	echo create_years('1900',date('Y'),'year_select',$year_select,$disabled, TRUE);?>
		   			<label for="year_select" class="active">Year<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				 	<?php 
					$month_select = NULL;
					$disabled = FALSE;
					if($remittance_info['year_month'])
					{
						$month_select = substr($remittance_info['year_month'],4);
					}
					if($action == ACTION_VIEW OR $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING) $disabled = TRUE;
					 echo create_months('month_select',$month_select,FALSE,$disabled);?>
		   			<label for="month_select" class="active">Month<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				 <label for="certified_by" class="active">Certified By<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				 <select  id="certified_by" name="certified_by" class="selectize validate" placeholder="Select Employee.." <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?> required>
					<option></option>
					<?php 
						foreach($certified_by AS $r)
						{
							echo '<option value="' . $r['employee_id'] . '">' . $r['fullname'] . '</option>';
						}
					?>
			 	 </select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				 <label for="approved_by" class="active">Approved By<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
				 <select id="approved_by" name="approved_by" class="selectize validate" placeholder="Select Employee.." <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?> required>
					<option></option>
					<?php 
						foreach($approved_by AS $r)
						{
							echo '<option value="' . $r['employee_id'] . '">' . $r['fullname'] . '</option>';
						}
					?>
			 	 </select>
				</div>
			</div>
		</div>
		<?php if($remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING OR ($action == ACTION_VIEW AND $remittance_info['remittance_status_id'] != REMITTANCE_FOR_REMITTANCE)) : ?>
		<input type="hidden" name="remittance_type_id" value="<?php echo $remittance_info['remittance_type_id'] ?>">
		<input type="hidden" name="deduction_start_date" value="<?php echo $remittance_info['deduction_start_date'] ?>">
		<input type="hidden" name="deduction_end_date" value="<?php echo $remittance_info['deduction_end_date'] ?>">
		<input type="hidden" name="certified_by" value="<?php echo $remittance_info['certified_by'] ?>">
		<input type="hidden" name="approved_by" value="<?php echo $remittance_info['approved_by'] ?>">
		<input type="hidden" name="payroll_type_flag" value="<?php echo $remittance_info['payroll_type_flag'] == PAYOUT_TYPE_FLAG_VOUCHER ? 'V' : '' ?>">
		<?php
			if($remittance_info['year_month'])
			{
				$year_select = substr($remittance_info['year_month'],0,4);
				$month_select = substr($remittance_info['year_month'],4);
				echo '<input type="hidden" name="year_select" value="'.$year_select.'">
						<input type="hidden" name="month_select" value="'.round($month_select).'">';
			}

		?>
		
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				 	<input class="validate datepicker" name="or_date" id="or_date" type="text"  value="<?php echo (!EMPTY($remittance_info['or_date']) ? format_date($remittance_info['or_date']) : '') ?>" />
		   			<label for="or_date">OR Date <span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				 	<input class="validate" name="or_no" id="or_no" type="text" value="<?php echo (!EMPTY($remittance_info['or_no']) ? $remittance_info['or_no'] : '') ?>"/>
		   			<label for="or_number">OR No. <span class="required">*</span></label>
				</div>
			</div>
		</div>
			<?php if($remittance_info['employer_share_flag'] == YES) 
			{ ?>
				<div class="row">
					<div class="col s6">
						<div class="input-field">
						 	<input class="validate datepicker" name="or_date_gs" id="or_date_gs" type="text"  value="<?php echo (!EMPTY($remittance_info['or_date_gs']) ? format_date($remittance_info['or_date_gs']) : '') ?>" />
				   			<label for="or_date">OR Date (Gov't Share) <span class="required">*</span></label>
						</div>
					</div>
					<div class="col s6">
						<div class="input-field">
						 	<input class="validate" name="or_no_gs" id="or_no_gs" type="text" value="<?php echo (!EMPTY($remittance_info['or_no_gs']) ? $remittance_info['or_no_gs'] : '') ?>"/>
				   			<label for="or_number">OR No. (Gov't Share) <span class="required">*</span></label>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php endif;?>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				 	<input disabled id="remittance_status_id" type="text" value="<?php echo (!EMPTY($remittance_info['remittance_status_name']) ? $remittance_info['remittance_status_name'] : 'For Remittance') ?>" />
				 	<input name="remittance_status_id" type="hidden" value="<?php echo (!EMPTY($remittance_info['remittance_status_id']) ? $remittance_info['remittance_status_id'] : '') ?>" />
				 	<label for="remittance_status_id" class="active">Status</label>
				</div>
			</div>
			<!-- <div class="col s6 form-styled">
			  <div class="input-field">
			    <input type="checkbox" class="labelauty" name="payroll_type_flag" id="payroll_type_flag" value="<?php echo PAYOUT_TYPE_FLAG_VOUCHER ?>" data-labelauty="Payroll|Voucher" <?php echo $remittance_info['payroll_type_flag'] == PAYOUT_TYPE_FLAG_VOUCHER ? 'checked' : '' ?>  <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_REMITTED ? 'disabled' : '' ?>/>
			    
		      </div> -->
			<div class='col s6 switch p-md p-r-n'>
				<label>
					Payroll
					<input class="validate" id="payroll_type_flag" name='payroll_type_flag' type='checkbox' value="<?php echo PAYOUT_TYPE_FLAG_VOUCHER ?>" <?php echo $remittance_info['payroll_type_flag'] == PAYOUT_TYPE_FLAG_VOUCHER ? 'checked' : '' ?>  <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?>>
					<span class='lever active' ></span>
					Voucher
				</label>
			</div>
			<!-- <input id="payroll_type_flag" name='payroll_type_flag' type='hidden' value="<?php echo $remittance_info['payroll_type_flag'] ?>"> -->
					
		</div>
		<?php if($remittance_info['payroll_type_flag'] == PAYOUT_TYPE_FLAG_REGULAR OR $action == ACTION_ADD) : ?>
		<div id="payroll_type_row" class="row">
			<div class="col s12">
				<div class="input-field">
				 	<label for="payroll_type" class="active">Payroll Type<?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? '' : '<span class="required">&nbsp;*</span>' ?></label>
					 <select id="payroll_type" name="payroll_type[]" class="selectize validate" placeholder="Select Payroll Type.." <?php echo $remittance_info['remittance_status_id'] == REMITTANCE_PROCESSING ? 'disabled' : '' ?> required multiple="">
						<option></option>
						<?php 
							foreach($payroll_types AS $r)
							{
								echo '<option value="' . $r['payroll_type_id'] . '">' . $r['payroll_type_name'] . '</option>';
							}
						?>
				 	 </select>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<br/><br/><br/><br/>
	<div class="md-footer default">
		<?php if($action != ACTION_VIEW): ?>
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="modal_cancel">Cancel</a>
	    <button id="save_remittance" class="btn btn-success " id="save_remittance" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		<?php endif; ?>
	</div>
</form>

<script>

	$(function (){
	$('#remittance_form').parsley();
	$('#remittance_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_remittance', 1);
		  	var option = {
					url  : $base_url + '<?php echo PROJECT_MAIN ?>/payroll_remittance/process_remittance',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_remittance.closeModal();
							load_datatable('payroll_remittance_list_tbl', '<?php echo PROJECT_MAIN ?>/payroll_remittance/get_payroll_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_remittance', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  	
	$('#payroll_type_flag').change(function(){
			if($(this).is(":checked")) {
				$('#payroll_type_row').attr('style','display: none !important;');
				$('#payroll_type').removeAttr('required');
			} else {
				$('#payroll_type_row').removeAttr('style');
				$('#payroll_type').attr('required',true);
			}
   		
	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

  	// TO ENTIRELY REMOVE THE ASTERISKS(*) AND DISABLE ALL FIELDS IN THIS VIEW
	<?php if($action == ACTION_VIEW) : ?>
		$('span.required').addClass('none');
		$('.validate').attr('disabled','');
	<?php endif; ?>

	// evaluation_start_date
	/*$('#deduction_start_date').change(function(){
		$('#deduction_end_date').val('');
	});*/
})

</script>