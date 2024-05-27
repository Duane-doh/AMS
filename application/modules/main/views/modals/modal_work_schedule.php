<form id="role_form">
	<div class="row m-t-md">
		<div class="row">
			<div class="col s2 p-l-xl">
				<img class="circle" width="65" height="65" src="<?php echo base_url().PATH_IMAGES. 'avatar/avatar_001.jpg'?>"/>
			</div>
			<div class="col s10 m-l-n-sm">
				<div class="row m-t-sm">
					<div class="col s6">
						<label class="dark font-xl">Juan Dela Cruz</label>
					</div>
					<div class="col s6">
						<div class="col s4">
							<label class="font-md dark">Status:</label>
						</div>
						<div class="col s8">
							<label class="font-md">Contractual</label>
						</div>
					</div>
				</div>
				<div class="row m-t-n-md">
					<div class="col s6">
						<label class="font-md">1115637-2</label>
					</div>
					<div class="col s6">
						<div class="col s4">
							<label class="font-md dark">Office: </label>
						</div>
						<div class="col s8">
							<label class="font-md">Bureau of Quarantine</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
		<div class="row">
		  	<div class="col s4 p-l-lg m-l-md">
		  		
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="sunday" class="ind_checkbox"/> <label for="sunday">Sunday</label>
				</div>
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="monday" class="ind_checkbox" /> <label for="monday"> Monday</label>
				</div>
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="tuesday" class="ind_checkbox" /> <label for="tuesday"> Tuesday</label>
				</div>
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="wednesday" class="ind_checkbox" /> <label for="wednesday"> Wednesday</label>
				</div>
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="thursday" class="ind_checkbox" /> <label for="thursday"> Thursday</label>
				</div>
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="friday" class="ind_checkbox" /> <label for="friday"> Friday</label>
				</div>
			  	<div class="col s12 m-t-sm">
					<input type="checkbox" name="days" id="saturday" class="ind_checkbox"/> <label for="saturday"> Saturday</label>
				</div>
		  	</div>
		  	<div class="col s7 m-t-xl">
			  	<div class="form-float-label" >
			  		<div class="row  b-t b-light-gray">
					  	<div class="col s6">
							<div class="input-field">
							  <input id="time_in" name="time_in" value="" type="text" class="validate timepicker">
							  <label for="time_in">Time In</label>
							</div>
						</div>	
						<div class="col s6">
							<div class="input-field">
							  <input id="time_out" name="time_out" value="" type="text" class="validate timepicker">
							  <label for="time_out">Time Out</label>
							</div>
						</div>	
					</div>
					<div class="row">
						<div class="col s6">
							<div class="input-field">
							  <input id="break_in" name="break_in" value="" type="text" class="validate timepicker">
							  <label for="break_in">Break In</label>
							</div>
						</div>	
						<div class="col s6">
							<div class="input-field">
							  <input id="break_out" name=break_out" value="" type="text" class="validate timepicker">
							  <label for="break_out">Break Out</label>
							</div>
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