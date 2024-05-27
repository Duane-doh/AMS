<form class="m-b-md" id="form_declaration">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<div class="form-float-label">
		<div class="row b-t b-light-gray">
			<div class="col s12">
				<div class="input-field">
				  <input id="govt_issued_id" name="govt_issued_id" type="text" class="validate" required value="<?php echo isset($declaration['govt_issued_id'])? $declaration['govt_issued_id']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="govt_issued_id">Government Issued ID<span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row b-t b-light-gray">
			<div class="col s12">
				<div class="input-field">
				  <input id="ctc_no" name="ctc_no" type="text" class="validate" required value="<?php echo isset($declaration['ctc_no'])? $declaration['ctc_no']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="ctc_no">ID/License/Passport No.<span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
				  <input id="issued_place" name="issued_place" type="text" class="validate" required value="<?php echo isset($declaration['issued_place'])? $declaration['issued_place']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="issued_place">Place Issued<span class="required">*</span></label>
				</div>
			</div>
		  	<div class="col s6">
				<div class="input-field">
				  <input id="issued_date" name="issued_date" type="text" required class="validate datepicker" placeholder="YYYY/MM/DD"  
					  	 onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'issued_date')"
						 value="<?php echo isset($declaration['issued_date'])? date_format(date_create($declaration['issued_date']), 'Y/m/d') :"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>>
				  <label for="issued_date" class="active">Date Issued<span class="required">*</span></label>
				</div>
			</div>
		</div> 
	</div>
</form>
<div class="right-align m-t-lg">
	<?php IF ($action != ACTION_VIEW): ?>
		<button class="btn btn-success " type="submit" name="action" id="save_declaration">Save</button>
	<?php ENDIF; ?>
	</div>
<script type="text/javascript">
$(function(){
	<?php if($action != ACTION_ADD): ?>
		$(".input-field label").addClass("active");
	<?php endif; ?>	

	jQuery(document).off('click', '#save_declaration');
	jQuery(document).on('click', '#save_declaration', function(e){	
	
		$("#form_declaration").trigger('submit');
	});
 	$('#form_declaration').parsley();

 	jQuery(document).off('submit', '#form_declaration');
	jQuery(document).on('submit', '#form_declaration', function(e){
	    e.preventDefault();
	    if ( $(this).parsley().isValid() ) {

	  		var data = $('#form_declaration').serialize();
	  		var process_url = "";
	  		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_declaration';
			<?php else: ?>
				process_url = $base_url + 'main/pds_declaration_info/process';
			<?php endif; ?>
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