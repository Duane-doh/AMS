<form id="benefits_form">

	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="benefits">Benefit code</label>
					 <select id="frequency" name="frequency" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Frequency">
						<option value="">Select Frequency</option>
						<option value="1">A</option>
					  </select>
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="benefits">Benefit Name</label>
					<input id="benefits" required="true" aria-required="true"  name="benefits" type="text" class="validate">
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="amount">Amount</label>
					<input id="amount" name="amount" type="text" class="validate">
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="frequency" class="active">Frequency</label>
					  <select id="frequency" name="frequency" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Frequency">
						<option value="">Select Frequency</option>
						<option value="1">A</option>
					  </select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="taxable">Payout Schedule</label>
					<input id="taxable" name="taxable" type="text" class="validate">
				</div>
			</div>
		</div>
		<div class="row m-n">
			<div id="date" class="hide">  
				<div class="col s6">
					<div class="input-field">
						<label for="start_date">Start Date</label>
						<input id="start_date" name="start_date" type="text" class="datepicker">
					</div>
				</div>
				<div class="col s6">
					<div class="input-field">
						<label for="end_date">End Date</label>
						<input id="end_date" name="end_date" type="text" class="datepicker">
					</div>
				</div>
			</div>
		</div>
		
	</div>	
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		    <button class="btn btn-success " id="save_benefits" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){

	$('#benefits_form').parsley();
	$('#benefits_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
			//var id = $('#emp_id').val();

		  	button_loader('save_benefits', 1);
		  	var option = {
					url  : $base_url + 'main/compensation/process_compensation/',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_bank").trigger('click');
							load_datatable('bank_table', '<?php echo PROJECT_MAIN ?>/compensation/get_benefit_list');
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_benefits', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

})
</script>