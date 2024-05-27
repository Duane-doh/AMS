<form class="m-b-md" id="form_position_description">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<div class="form-float-label">
		<div class="row b-t b-light-gray">
			<div class="col s4">
				<div class="input-field">
				  <input id="position_designation" name="position_designation" type="text" class="validate"  value="<?php echo isset($description['position_designation'])? $description['position_designation']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="position_designation">8. OFFICIAL, DESIGNATION OF POSITION</label>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
				  <input id="proposed_title" name="proposed_title" type="text" class="validate"  value="<?php echo isset($description['proposed_title'])? $description['proposed_title']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="proposed_title">9. WORKING OR PROPOSED TITLE </label>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
				  <input id="position_classification" name="position_classification" type="text" class="validate"  value="<?php echo isset($description['position_classification'])? $description['position_classification']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="position_classification">10. OCPC CLASSIFICATION OF POSITION</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				  <input id="immediate_position" name="immediate_position" type="text" class="validate"  value="<?php echo isset($description['immediate_position'])? $description['immediate_position']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="immediate_position">14. POSITION TITLE OF IMMEDIATE SUPERVISOR</label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
				  <input id="next_higher_position" name="next_higher_position" type="text" class="validate"  value="<?php echo isset($description['next_higher_position'])? $description['next_higher_position']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="next_higher_position">15. POSITION TITLE OF NEXT HIGHER SUPERVISOR</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
				  <input id="directly_supervised" name="directly_supervised" type="text" class="validate"  value="<?php echo isset($description['directly_supervised'])? $description['directly_supervised']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="directly_supervised" class="font-sm">16. NAMES, TITLES AND ITEM NUMBERS OF THOSE WHO YOU DIRECTLY SUPERVISE. (If more than seven, list only by their item number and titles)</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
				  <input id="work_tools_used" name="work_tools_used" type="text" class="validate"  value="<?php echo isset($description['work_tools_used'])? $description['work_tools_used']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="work_tools_used">17. MACHINES, EQUIPMENTS, TOOLS, ETC. USED REGULARLY IN PERFORMANCE OF WORK.</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="row">
					<div class="col s6">
						<label class="font-md text-uppercase">18. C O N T A C T S</label>
					</div>
					<div class="col s3 p-t-sm">
						<label class="font-md text-uppercase">OCCASIONAL</label>
					</div>
					<div class="col s3 p-t-sm">
						<label class="font-md text-uppercase">FREQUENT</label>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s6 p-t-sm">
						<label class="font-md text-uppercase p-l-xl">General Public</label>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="general_public"  value="GP-O" <?php echo (in_array('GP-O', $contacts)) ? ' checked ' : '' ?> data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="general_public"  value="GP-F" <?php echo (in_array('GP-F', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s6 p-t-sm">
						<label class="font-md text-uppercase p-l-xl">Other Agency</label>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="other_agency"  value="OA-O" <?php echo (in_array('OA-O', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="other_agency"  value="OA-F" <?php echo (in_array('OA-F', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s6 p-t-sm">
						<label class="font-md text-uppercase p-l-xl">Supervisors</label>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="supervisors"  value="S-O" <?php echo (in_array('S-O', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="supervisors"  value="S-F" <?php echo (in_array('S-F', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s6 p-t-sm">
						<label class="font-md text-uppercase p-l-xl">Management</label>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="management"  value="M-O" <?php echo (in_array('M-O', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="management"  value="M-F" <?php echo (in_array('M-F', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s6 p-t-sm">
						<label class="font-md p-l-xl">OTHERS (Specify)</label>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="contact_others"  value="O-O" <?php echo (in_array('O-O', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s3 p-l-lg">
						<input type="radio" class="labelauty" name="contact_others"  value="O-F" <?php echo (in_array('O-F', $contacts)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
				</div>
			</div>
			<div class="col s6 p-l-md">
				<div class="row p-b-md">
					<div class="col s12">
						<label class="font-md text-uppercase">19. WORKING CONDITION</label>
					</div>
				</div>
				<div class="row">
					<div class="col s3 p-l-lg">
						<input type="checkbox" class="labelauty" name="working_condition[]"  value="NWC" <?php echo (in_array('NWC', $working_condition)) ? ' checked ' : '' ?> data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s9 p-t-sm">
						<label class="font-md text-uppercase">NORMAL WORKING CONDITION</label>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s3 p-l-lg">
						<input type="checkbox" class="labelauty" name="working_condition[]"  value="FW" <?php echo (in_array('FW', $working_condition)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s9 p-t-sm">
						<label class="font-md text-uppercase">FIELD WORK</label>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s3 p-l-lg">
						<input type="checkbox" class="labelauty" name="working_condition[]"  value="FT" <?php echo (in_array('FT', $working_condition)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s9 p-t-sm">
						<label class="font-md text-uppercase">FIELD TRIPS</label>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s3 p-l-lg">
						<input type="checkbox" class="labelauty" name="working_condition[]"  value="EVWC" <?php echo (in_array('EVWC', $working_condition)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s9 p-t-sm">
						<label class="font-md text-uppercase">EXPOSED TO VARIED WEATHER CONDITION</label>
					</div>
				</div>
				<div class="row p-t-xs">
					<div class="col s3 p-l-lg">
						<input type="checkbox" class="labelauty" name="working_condition[]"  value="O" <?php echo (in_array('O', $working_condition)) ? ' checked ' : '' ?>data-labelauty="" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
					</div>
					<div class="col s9 p-t-sm">
						<label class="font-md text-uppercase">OTHERS (Specify)</label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<label class="font-md text-uppercase">21. Describe briefly the general function of the Unit or Section.</label>
			</div>
		</div>
		<div class="row b-t-n">
			<div class="col s12">
				<div class="input-field">
					<textarea type="text" name="unit_general_function" id="unit_general_function" aria-required="true"  class="materialize-textarea" placeholder="Describe briefly the general function of the Unit or Section." <?php echo ($action == ACTION_VIEW) ? 'disabled':''?>><?php echo isset($description['unit_general_function']) ? $description['unit_general_function'] : ""?></textarea>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="right-align m-t-lg">
	<?php IF ($action != ACTION_VIEW): ?>
		<button class="btn btn-success " type="submit" name="action" id="save_position_description">Save</button>
	<?php ENDIF; ?>
	</div>
<script type="text/javascript">
$(document).ready(function(){
	<?php if($action != ACTION_ADD): ?>
		$(".input-field label").addClass("active");
	<?php endif; ?>	
	CKEDITOR.replace('unit_general_function', {
	    removePlugins: 'toolbar'
	});
	jQuery(document).off('click', '#save_position_description');
	jQuery(document).on('click', '#save_position_description', function(e){	
	
		$("#form_position_description").trigger('submit');
	});
 	$('#form_position_description').parsley();

 	jQuery(document).off('submit', '#form_position_description');
	jQuery(document).on('submit', '#form_position_description', function(e){
	    e.preventDefault();

	    $('#unit_general_function').val(CKEDITOR.instances['unit_general_function'].getData());

	    if ( $(this).parsley().isValid() ) {

	  		var data = $('#form_position_description').serialize();
	  		var process_url = $base_url + 'main/pds_position_description_info/process';
		   $('#tab_content').isLoading();
		    var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
								notification_msg("<?php echo SUCCESS ?>", result.message);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						 $('#tab_content').isLoading( "hide" );
					}
			};
			General.ajax(option);  		    
	      }

	});
});
</script>