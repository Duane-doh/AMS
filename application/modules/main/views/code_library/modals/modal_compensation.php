<form id="compensation_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required name="compensation_name" id="compensation_name" value="<?php echo isset($compensation_info['compensation_name']) ? $compensation_info['compensation_name'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="compensation_name">Compensation Name<span class="required">*</span></label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<input type="text" class="validate" required name="compensation_code" id="compensation_code" value="<?php echo isset($compensation_info['compensation_code']) ? $compensation_info['compensation_code'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="compensation_code">Compensation Code<span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s4 b-r-n">
			</div>
			<div class="col s2  b-r-n">
		  		<input type="radio" id="fixed" name="compensation_type_flag" value="F" <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($compensation_info['compensation_type_flag'] == "F") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
				<label for="fixed">Fixed</label> <br>
			</div>
			<div class="col s2  b-r-n">
				<input type="radio" id="variable" name="compensation_type_flag" value="V" <?php echo ($compensation_info['compensation_type_flag'] == "V") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
				<label for="variable">Variable</label>
			</div>
			<div class="col s4">
			</div>
		</div>
		<div class="none" id="amount" class="row m-t-md">
			<div class="form-float-label" >
				<div class="row b-t b-light-gray m-n">
				 	<div class="col s12">
						<div class="input-field">
						 	<input type="text" aria-required="true" name="amount" id="amount" value="<?php echo isset($compensation_info['amount']) ? $compensation_info['amount'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
							<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="amount">Amount<span class="required">*</span></label>
						</div>
					</div>
				 </div>
			</div>
		</div>
		<div class="none" id="variable_group" class="row m-t-md">
			<div class="form-float-label">
				<div class="row">
					<div class="col s6">
						<div class="input-field">
							<label for="multiplier" class="active">Base Multiplier<span class="required">*</span></label>
							<select id="multiplier" name="multiplier" class="selectize" placeholder="Select Base Multiplier" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
							 <option value="">Select Base Multiplier</option>
							 <?php if (!EMPTY($multiplier_name)): ?>
								<?php foreach ($multiplier_name as $dt): ?>
									<option value="<?php echo $dt['multiplier_id']?>"><?php echo $dt['multiplier_name'] ?></option>
								<?php endforeach;?>
							<?php endif;?>
							</select>
						</div>
				 	</div>
					<div class="col s6">
						<div class="input-field">
							<input type="text" aria-required="true" name="rate" id="rate" value="<?php echo isset($compensation_info['rate']) ? $compensation_info['rate'] : NULL; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
							<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="rate">Rate<span class="required">*</span></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col 6">
				<div class="input-field">
					<label for="frequency" class="active">Frequency<span class="required">*</span></label>
					<select id="frequency" name="frequency" class="selectize" placeholder="Select Frequency" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 <option value="">Select Frequency</option>
					 <?php if (!EMPTY($frequency_name)): ?>
						<?php foreach ($frequency_name as $dt): ?>
							<option value="<?php echo $dt['frequency_id']?>"><?php echo $dt['frequency_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="payout_schedule" class="active">Payout Schedule<span class="required">*</span></label>
					<select id="payout_schedule" name="payout_schedule" class="selectize" placeholder="Select Payout Schedule" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					<option value="">Select Payout Schedule</option>
					<?php if (!EMPTY($payout_schedule_name)): ?>
						<?php foreach ($payout_schedule_name as $dt): ?>
							<option value="<?php echo $dt['payout_schedule_id']?>"><?php echo $dt['payout_schedule_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class='switch p-md'>
					Grant to employee?dfdsf<span class="required">*</span><br><br>
				    <label>
				        No
				        <input name='employee_flag' type='hidden'   value='N'>
				        <input name='employee_flag' type='checkbox'   value='Y' <?php echo ($compensation_info['employee_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
			<div class="col s6">
				<div class='switch p-md'>
					Taxable?<span class="required">*</span><br><br>
				    <label>
				    	No
				        <input name='taxable_flag' type='hidden'   value='N'>
				        <input name='taxable_flag' type='checkbox'   value='Y' <?php echo ($compensation_info['taxable_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				        <span class='lever'></span>Yes
				    </label>
				</div>
			</div>
		</div>
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="bir_form" class="active">BIR Form 2316 Section<span class="required">*</span></label>
					<select id="bir_form" name="bir_form" class="selectize" placeholder="Select BIR Form 2316 Section" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 	<!-- <option value=""></option> -->
					 	<option value="21">21</option>
					 	<option value="22">22</option>
					 	<option value="23">23</option>
					 	<option value="24">24</option>
					 	<option value="25">25</option>
					 	<option value="26">26</option>
					 	<option value="27">27</option>
					 	<option value="28">28</option>
					 	<option value="29">29</option>
					 	<option value="30">30</option>
					 </select>
				</div>
		 	</div>
		</div>
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="dbm_form_c" class="active">DBM Form 703 C<span class="required">*</span></label>
					<select id="dbm_form_c" name="dbm_form_c" class="selectize" placeholder="Select DBM Form 703 C" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 	<!-- <option value=""></option> -->
					 	<option value="1">1</option>
					 	<option value="2">2</option>
					 	<option value="3">3</option>
					 	<option value="4">4</option>
					 	<option value="5">5</option>
					 </select>
				</div>
		 	</div>
		</div>
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="dbm_form_c1" class="active">DBM Form 703 C1<span class="required">*</span></label>
					<select id="dbm_form_c1" name="dbm_form_c1" class="selectize" placeholder="Select DBM Form 703 C1" multiple <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
					 	<!-- <option value=""></option> -->
					 	<option value="Incentive1">Incentive 1</option>
					 	<option value="Incentive2">Incentive 2</option>
					 	<option value="Incentive3">Incentive 3</option>
					 	<option value="Separation1">Separation 1</option>
					 	<option value="Separation2">Separation 2</option>
					 	<option value="Separation3">Separation 3</option>
					 </select>
				</div>
		 	</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='hidden'   value='N'>
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($compensation_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_compensation">Cancel</a>
		    <button class="btn btn-success " id="save_compensation" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#amount').removeClass('none');

	$( 'input[name="compensation_type_flag"]').on( "click", function() {
  		var selected = $('input[name="compensation_type_flag"]:checked').val();

  			if (selected === 'V') {
  				$('#variable_group').removeClass('none');
  				$('#amount').addClass('none');
  			}

  			if (selected === 'F') {
  				$('#variable_group').addClass('none');
  				$('#amount').removeClass('none');
  			}
	});

	$('#compensation_form').parsley();
	$('#compensation_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_compensation', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/process_compensation',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_compensation").trigger('click');
							load_datatable('compensations_table', '<?php echo PROJECT_MAIN ?>/code_library_hr/get_compensation_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_compensation', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
		<?php if ($compensation_info['compensation_type_flag'] == "V"):?>
				$('#variable_group').removeClass('none');
  				$('#amount').addClass('none');
		<?php endif;?>
  	<?php } ?>
})
</script>