<form id="contract_record_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	
  <div class="scroll-pane" style="height: 300px">
	<div class="form-float-label">
    <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		  	<input id="contract_number" name="contract_number" value="<?php echo (($action==ACTION_EDIT) OR ($action==ACTION_VIEW))  ? '001' : ''  //echo !EMPTY($employee_contract_record['contract_record_number']) ? $employee_contract_record['contract_record_number'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		    <label for="contract_number">Contract Number</label>
		  </div>
	    </div>
    </div>
	  <div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		  	<input id="contract_record_start" name="contract_record_start" value="<?php echo (($action==ACTION_EDIT) OR ($action==ACTION_VIEW))  ? '08-21-2016' : '' //echo !EMPTY($employee_contract_record['contract_record_start']) ?  $employee_contract_record['contract_record_start'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker datepicker_start" >
		    <label for="contract_record_start">Service Start</label>
	      </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		  	<input id="contract_record_end" name="contract_record_end" value="<?php echo (($action==ACTION_EDIT) OR ($action==ACTION_VIEW))  ? '08-21-2017' : '' // echo !EMPTY($employee_contract_record['contract_record_end']) ? $employee_contract_record['contract_record_end'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate datepicker datepicker_end">
		    <label for="contract_record_end">Service End</label>
	      </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s6">
		 <div class="input-field">
		  	<label for="position_name" class="active">Position</label>
			<select id="position_name" name="position_name" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Designation">
				<option value="">Select Position</option>
				<option value="1">Utility Worker 1</option>
				<option value="2">Utility Worker 2</option>
				<?php foreach($param_position as $row):?>
					<option value="<?php echo $row['position_id']; ?>"><?php echo $row['position_name']; ?></option>
				<?php endforeach; ?>
			</select>
	      </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		  	<input id="office_name" name="office_name" value="<?php echo (($action==ACTION_EDIT) OR ($action==ACTION_VIEW))  ? 'DOH KMS' : '' //echo !EMPTY($employee_contract_record['office_id']) ? $employee_contract_record['office_id'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		    <label for="office_name">Office Name</label>
		  </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		  	<input id="salary_grade" name="salary_grade" value="<?php echo (($action==ACTION_EDIT) OR ($action==ACTION_VIEW))  ? 'GRADE 1' : '' //echo !EMPTY($employee_contract_record['salary_grade']) ? $employee_contract_record['salary_grade'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		    <label for="salary_grade">Salary Grade</label>
		  </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		  	<input id="salary_step" name="salary_step" value="<?php echo (($action==ACTION_EDIT) OR ($action==ACTION_VIEW))  ? 'STEP 1' : '' //echo !EMPTY($employee_contract_record['salary_step']) ? $employee_contract_record['salary_step'] : '' ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> type="text" class="validate">
		    <label for="salary_step">Salary Step</label>
		  </div>
	    </div>
	   </div>
  </div>
  </div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <!-- <button class="btn btn-success " id="save_contract_record" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button> -->
	    <a class="waves-effect waves-teal btn cancel_modal">Save</a>
	  <?php //endif; ?>
	</div>
</form>
<?php $url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module; ?>
<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>


	$('#contract_record_form').parsley();
	$('#contract_record_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_contract_record', 1);
		  	var option = {
					url  : $base_url + 'main/contract_record/process_employee_contract_record',
					data : data,
					success : function(result){
						if(result.status)
						{
							modal_contract_record.closeModal();
							load_datatable('table_employee_contract_record', '<?php echo PROJECT_MAIN ?>/contract_record/get_employee_contract_record/',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_contract_record', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>
