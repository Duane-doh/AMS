<form id="form_supporting">
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		  	<label for="address_type_id" class="active">Document Type</label>
			<select id="address_type_id" name="address_type_id" class="selectize" placeholder="Select Document Type">
				<option value="">Select Address Type</option>
				<option value="1">Birth Certificate</option>
				<option value="2">Baptismal Certificate</option>				
			</select>
	      </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		  	<input id="date_received" name="date_received" type="text" class="validate datepicker">
		    <label for="date_received">Date Received</label>
	      </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		  	<label for="address_value">Remarks</label>
			<textarea type="text" name="address_value" id="address_value" required="" aria-required="true"  class="materialize-textarea"></textarea>				
	      </div>
	    </div>
	  </div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn btn-success " type="button" id="save_address" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>	  
	</div>
</form>