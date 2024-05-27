<form id="attendance_remarks_form">
	<input type="hidden" name="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" name="attendance_date" value="<?php echo $attendance_date ?>"/>
	<input type="hidden" name="time_flag" value="<?php echo $time_flag ?>"/>
	<div class="form-float-label">	
		<div class="row">	
			<div class="col s12">
				<div class="input-field">
				    <label for="attendance_status_id" class="active">Type<span class="required">*</span></label>
					<select id="attendance_status_id" name="attendance_status_id"  required class="selectize" placeholder="Select Relationship" <?php echo ($edit_flag) ? '':'disabled'?>>
						<option value="">Select Type</option>
						<?php if (!EMPTY($attendance_status)): ?>
							<?php foreach ($attendance_status as $type): ?>
								<?php 
									$selected = "";
									$selected = ($type['attendance_status_id'] == $attendance_info['attendance_status_id']) ? " selected ":"";
								?>
								<option value="<?php echo $type['attendance_status_id'] ?>" <?php echo $selected;?>><?php echo strtoupper($type['attendance_status_name']) ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
			    </div>
			</div>
		</div>	
		<div class="row">
	      <div class="col s12">
	        <div class="input-field">
	          <label for="remarks" class="active">Remarks</label>
	          <textarea id="remarks" name="remarks" required class="materialize-textarea" <?php echo ($edit_flag) ? '':'disabled'?> ><?php echo (isset($attendance_info['remarks'])) ? $attendance_info['remarks']: ''?></textarea>
	        </div>
	      </div>
    	</div>
	</div>
<div class="md-footer default">
	<?php if($edit_flag):?>
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button class="btn btn-success " id="save_remarks" value="Save">Save</button>
	<?php endif; ?>
</div>
</form>

<script> 
$(document).ready(function(){
	$('#attendance_remarks_form').parsley();
	jQuery(document).off('submit', '#attendance_remarks_form');
	jQuery(document).on('submit', '#attendance_remarks_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#attendance_remarks_form').serialize();
		  	button_loader('save_remarks', 1);
		  	var option = {
					url  : $base_url + 'main/employee_attendance/save_attendance_remarks',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_attendance_remarks.closeModal();
						
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_remarks', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});
</script>