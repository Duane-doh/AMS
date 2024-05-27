<form id="role_form">
	<div class="form-float-label">
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  <label for="loan_type" class="active">Compensation Type</label>
			  <select id="loan_type" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Compensation">
				<option value="">Select Compensation</option>
				<option value="1">Transportation Allowance</option>
				<option value="2">Meal Allowance</option>
				<option value="3">Laundry Allowance</option>
			  </select>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  <label for="frequency" class="active">Frequency</label>
			  <select id="frequency" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Frequency">
				<option value="">Select Frequency</option>
				<option value="1">Daily</option>
				<option value="2">Monthly</option>
			  </select>
			</div>
		  </div>
		</div>
		<div class="row">
		  <div class="col s6">
			<div class="input-field">
			  <input id="Amount" name="Amount" value="" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
			  <label for="Amount">Amount</label>
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  <input id="num_of_months" name="num_of_months" value="" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
			  <label for="num_of_months">No. of Months</label>
			</div>
		  </div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				  <input id="start_date" name="start_date" value="" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker_start">
				  <label for="start_date">Start Date</label>
				</div>
			 </div>	
			 <div class="col s6">
				<div class="input-field">
				  <input id="end_date" name="end_date" value="" <?php echo $action_id==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker_end">
				  <label for="end_date">End Date</label>
				</div>
			 </div>	
		</div>
	</div>
	<div class="row <?php echo $action_id ==ACTION_VIEW ? 'none' : '' ?>">
		<div class="row p-l-sm"><label class="dark font-md">Compensation Scope</label></div>
		<div class="col s3 m-r-n-xl">
			<input type="radio" class="labelauty" name="filter_type"  value="1" data-labelauty="All Employees" checked/>
			 </div>	
		 <div class="col s3">
			<input type="radio" class="labelauty" name="filter_type"  value="2" data-labelauty="Filter by Criteria"/>
		 </div>	
		  <div class="col s6 m-l-xl">
			<button type="button" class="btn pull-right none" id = "add_more_btn">Add More</button>
		 </div>	
	</div>
	<div class="none" id="filter_criteria">
		<div class="form-float-label" >
			<div class="row b-t b-light-gray">
			  <div class="col s6">
				<div class="input-field">
				  <label for="criteria_selected" class="active">Filter By</label>
				  <select id="criteria_selected" class="selectize" placeholder="Select Criteria">
					<option value="">Select Criteria</option>
					<option value="1">Office</option>
					<option value="2">Status</option>
					<option value="3">Position</option>
				  </select>
				</div>
			  </div>
			  <div class="col s6">
				<div class="input-field">
				  <label for="option_selected" class="active">Option</label>
				  <select id="option_selected" class="selectize" placeholder="Select ">
					<option value="">Select value</option>
					<option value="1">Value one</option>
					<option value="2">Value two</option>
				  </select>
				</div>
			  </div>
			</div>
		</div>
	</div>
</form>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_service_record">Cancel</a>
	    <button id="deductions" class="btn " value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	 </div>
<script>
$(document).ready(function(){
	$('input[name="filter_type"]').off('change');
	$('input[name="filter_type"]').on('change', function(e) {
		var selected = $('input[name="filter_type"]:checked').val();
	    if(selected === "2"){
	    	$('#filter_criteria').removeClass('none');
	    	$('#add_more_btn').removeClass('none');
	    }
	    else{
	    	$('#filter_criteria').addClass('none');
	    	$('#add_more_btn').addClass('none');
	    }
	});
	
});
</script>