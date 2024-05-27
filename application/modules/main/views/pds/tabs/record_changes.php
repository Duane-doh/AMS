<form class="m-b-md" id="form_declaration">
	<input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" id="salt" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" id="token" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" id="action" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" id="module" name="module" value="<?php echo $module ?>"/>

	<table class="striped table-default">
		<thead class="teal white-text">
			<tr>
				<td width = "60%" class="font-semibold">Record Type</td>
				<td width = "10%" class="font-semibold">Added</td>
				<td width = "10%" class="font-semibold">Updated</td>
				<td width = "10%" class="font-semibold">Deleted</td>
				<td width = "10%"></td>
			</tr>
		</thead>
		<tbody>
			<?php if(!EMPTY($requests['personal_info'])):?>
			<tr id="personal_info">
				<td>Personal Information</td>
				<td></td>
				<td>&#10004;</td>
				<td></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_PERSONAL_INFO ?>,'personal_info')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['identification'])):?>
			<tr id="identification">
				<td>Identification</td>
				<td><?php echo $requests['identification_add']?></td>
				<td><?php echo $requests['identification_edit']?></td>
				<td><?php echo $requests['identification_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_IDENTIFICATION ?>,'identification')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['address'])):?>
			<tr id="address">
				<td>Address Information</td>
				<td><?php echo $requests['address_add']?></td>
				<td><?php echo $requests['address_edit']?></td>
				<td><?php echo $requests['address_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_ADDRESS_INFO ?>,'address')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['contact'])):?>
			<tr id="contact">
				<td>Contact Information</td>
				<td><?php echo $requests['contact_add']?></td>
				<td><?php echo $requests['contact_edit']?></td>
				<td><?php echo $requests['contact_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_CONTACT_INFO?>,'contact')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['family'])):?>
			<tr id="family">
				<td>Family Information</td>
				<td><?php echo $requests['family_add']?></td>
				<td><?php echo $requests['family_edit']?></td>
				<td><?php echo $requests['family_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_FAMILY_INFO ?>,'family')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['education'])):?>
			<tr id="education">
				<td>Education</td>
				<td><?php echo $requests['education_add']?></td>
				<td><?php echo $requests['education_edit']?></td>
				<td><?php echo $requests['education_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_EDUCATION ?>,'education')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['eligibility'])):?>
			<tr id="eligibility">
				<td>Civil Service Eligibility</td>
				<td><?php echo $requests['eligibility_add']?></td>
				<td><?php echo $requests['eligibility_edit']?></td>
				<td><?php echo $requests['eligibility_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_ELIGIBILITY ?>,'eligibility')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['work_experience'])):?>
			<tr id="work_experience">
				<td>Work Experience</td>
				<td><?php echo $requests['work_experience_add']?></td>
				<td><?php echo $requests['work_experience_edit']?></td>
				<td><?php echo $requests['work_experience_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_WORK_EXPERIENCE ?>,'work_experience')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['profession'])):?>
			<tr id="profession">
				<td>Profession</td>
				<td><?php echo $requests['profession_add']?></td>
				<td><?php echo $requests['profession_edit']?></td>
				<td><?php echo $requests['profession_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_PROFESSION ?>,'profession')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['voluntary_work'])):?>
			<tr id="voluntary_work">
				<td>Voluntary Work</td>
				<td><?php echo $requests['voluntary_work_add']?></td>
				<td><?php echo $requests['voluntary_work_edit']?></td>
				<td><?php echo $requests['voluntary_work_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_VOLUNTARY_WORK ?>,'voluntary_work')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['training'])):?>
			<tr id="training">
				<td>Training</td>
				<td><?php echo $requests['training_add']?></td>
				<td><?php echo $requests['training_edit']?></td>
				<td><?php echo $requests['training_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_TRAININGS ?>,'training')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['other_info'])):?>
			<tr id="other_info">
				<td>Other Information</td>
				<td><?php echo $requests['other_info_add']?></td>
				<td><?php echo $requests['other_info_edit']?></td>
				<td><?php echo $requests['other_info_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_OTHER_INFO ?>,'other_info')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['question'])):?>
			<tr id="question">
				<td>Questionnaire</td>
				<td></td>
				<td>&#10004;</td>
				<td></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_QUESTION ?>,'question')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['reference'])):?>
			<tr id="reference">
				<td>References</td>
				<td><?php echo $requests['reference_add']?></td>
				<td><?php echo $requests['reference_edit']?></td>
				<td><?php echo $requests['reference_delete']?></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_REFERENCES ?>,'reference')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
			<?php if(!EMPTY($requests['declaration'])):?>
			<tr id="declaration">
				<td>Declaration</td>
				<td></td>
				<td>&#10004;</td>
				<td></td>
				<td class="table-actions">
					<?php if ($action != ACTION_VIEW): ?>
					<a class="delete" href="javascript:;" onclick = "delete_changes('<?php echo $id ?>',<?php echo TYPE_REQUEST_PDS_DECLARATION ?>,'declaration')"></a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
</form>
<div class="right-align m-t-lg">
	<?php IF ($action != ACTION_VIEW AND $requests['record_count'] > 0): ?>
		<button class="btn btn-success " type="submit" name="action" id="submit_request">Submit Request</button>
	<?php ENDIF; ?>
	</div>
<script type="text/javascript">
$(function(){
	
jQuery(document).off('click', '#submit_request');
jQuery(document).on('click', '#submit_request', function(e){

	  $('#confirm_modal').confirmModal({
	    topOffset : 0,
	    onOkBut : function() {
	    	var action = $('#action').val();
	    	var id = $('#id').val();
	    	var token = $('#token').val();
	    	var salt = $('#salt').val();
	    	var module = $('#module').val();
	      var data = {
	                'action': action,
	                'id'    : id,
	                'token' : token,
	                'salt'  : salt,
	                'module': module
	            };
	         	$.post($base_url + "main/pds_record_changes_requests/process_pds_request",data, function(result) {
	              	if(result.status){

	                	notification_msg("<?php echo SUCCESS ?>", result.message);
						setTimeout(function(){ window.location.reload() }, 2000);
						
		            } 
		            else {
		                notification_msg("<?php echo ERROR ?>", result.message);
		            }
	           }, 'json');
	    },
	    onCancelBut : function() {},
	    onLoad : function() {
	      $('.confirmModal_content h4').html('Are you sure you want to submit request?'); 
	      $('.confirmModal_content p').html('Your record changes will be submitted for approval.<br><br><b class="red-text">Note:</b> You are not allowed to edit your Personal Data Sheet until your current request be completely processed.');
	    },
	    onClose : function() {}
	  	});
	});
 	
});
function delete_changes(id, type, info_type){
 
  $('#confirm_modal').confirmModal({
    topOffset : 0,
    onOkBut : function() {
    	 
      var data = {
                'type': type,
                'id': id
            };
         $.post($base_url + "main/pds_record_changes_requests/delete_pds_changes",data, function(result) {
              if(result.status){
                notification_msg("<?php echo SUCCESS ?>", result.message);
              	$('#'+info_type).remove();
              } 
              else {
                notification_msg("<?php echo ERROR ?>", result.message);
              }
           }, 'json');
    },
    onCancelBut : function() {},
    onLoad : function() {
      $('.confirmModal_content h4').html('Are you sure you want to remove this changes?'); 
      $('.confirmModal_content p').html('This action will delete this record changes from the database and cannot be undone.');
    },
    onClose : function() {}
  });
}
</script>