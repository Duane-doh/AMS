<form id="form_voluntary_work">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		    <input type="text" class="validate" required name="volunteer_org_name" id="volunteer_org_name" value="<?php echo isset($voluntary['volunteer_org_name'])? $voluntary['volunteer_org_name']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="volunteer_org_name">Organization Name<span class="required">*</span></label>
	      </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required name="volunteer_org_address" id="volunteer_org_address" value="<?php echo isset($voluntary['volunteer_org_address'])? $voluntary['volunteer_org_address']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="volunteer_org_address">Organization Address<span class="required">*</span></label>
		  </div>
	    </div>
	  </div>
	<div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		    <input type="text" class="validate datepicker datepicker_start" required placeholder="YYYY/MM/DD" name="volunteer_start_date" id="volunteer_start_date" 
				   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'volunteer_start_date')"
		    	   value="<?php echo isset($voluntary['volunteer_start_date'])? format_date($voluntary['volunteer_start_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="volunteer_start_date" class="active">Date Started<span class="required">*</span></label>
	      </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		    <input type="text" class="validate datepicker datepicker_end" placeholder="YYYY/MM/DD" name="volunteer_end_date" id="volunteer_end_date" 
				   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'volunteer_end_date')"
		    	   value="<?php echo isset($voluntary['volunteer_end_date'])?format_date( $voluntary['volunteer_end_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> />
		    <label for="volunteer_end_date" class="active">Date Ended</label>
	      </div>
	    </div>
	</div>
	<div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate number" required name="volunteer_hour_count" id="volunteer_hour_count" value="<?php echo isset($voluntary['volunteer_hour_count'])? $voluntary['volunteer_hour_count']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="volunteer_hour_count">Number of Hours<span class="required">*</span></label>
		  </div>
	    </div>
	</div>
	<div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required name="volunteer_position" id="volunteer_position" value="<?php echo isset($voluntary['volunteer_position'])? $voluntary['volunteer_position']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="volunteer_position">Position/Nature of Work<span class="required">*</span></label>
		  </div>
	    </div>
	</div>
	</div>	
</form>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	<?php if($action != ACTION_VIEW):?>
    <button class="btn btn-success " id="save_voluntary_work" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	<?php endif;?>
</div>
<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_voluntary_work').parsley();
	jQuery(document).off('click', '#save_voluntary_work');
	jQuery(document).on('click', '#save_voluntary_work', function(e){	
		$("#form_voluntary_work").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_voluntary_work');
	jQuery(document).on('submit', '#form_voluntary_work', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_voluntary_work').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_voluntary_work';
			<?php else: ?>
				process_url = $base_url + 'main/pds_voluntary_work_info/process';
			<?php endif; ?>
		  	button_loader('save_voluntary_work', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_voluntary_work.closeModal();
							load_datatable('pds_voluntary_work_table', '<?php echo PROJECT_MAIN ?>/pds_voluntary_work_info/get_voluntary_wok_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_voluntary_work', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>