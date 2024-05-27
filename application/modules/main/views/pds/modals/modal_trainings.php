<form id="form_trainings">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		    <label for="training_title">Training Title<span class="required">*</span></label>
		    <input type="text" id="training_title" name="training_title" maxlength="250" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> required="" value="<?php echo isset($trainings['training_name'])? $trainings['training_name']:"" ?>">				
	      </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		    <input type="text" class="validate datepicker datepicker_start" required placeholder="YYYY/MM/DD" name="training_start_date" id="training_start_date" 
				   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'training_start_date')"
		    	   value="<?php echo isset($trainings['training_start_date'])? format_date($trainings['training_start_date']):"" ?>"/>
		    <label for="training_start_date" class="active">Date Started<span class="required">*</span></label>
	      </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		    <input type="text" class="validate datepicker datepicker_end" required placeholder="YYYY/MM/DD" name="training_end_date" id="training_end_date" 
				   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'training_end_date')"
		   		   value="<?php echo isset($trainings['training_end_date'])? format_date($trainings['training_end_date']):"" ?>"/>
		    <label for="training_end_date" class="active">Date Ended<span class="required">*</span></label>
	      </div>
	    </div>
	  </div>

	<!-- <div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		   	<input type="text" class="validate number" required name="training_hour_count" id="training_hour_count" value="<?php echo isset($trainings['training_hour_count'])? $trainings['training_hour_count']:"" ?>"/>
		    <label for="training_hour_count">Number of Hours<span class="required">*</span></label>
		  </div>
	    </div>
	    <div class="col s6">
		  <div class="input-field">
		   	<input type="text" class="validate" required name="training_type" id="training_type" value="<?php echo isset($trainings['training_type'])? $trainings['training_type']:"" ?>"/>
		   	 <label for="training_type">Training Type <span class="required">*</span></label>
		  </div>
	    </div>
	</div> -->

<!-- NCOCAMPO : Training Type change to Type of LD (Managerial/Supervisory/Technical/etc.) : START -->
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate number" required name="training_hour_count" id="training_hour_count" value="<?php echo isset($trainings['training_hour_count'])? $trainings['training_hour_count']:"" ?>"/>
		    <label for="training_hour_count">Number of Hours<span class="required">*</span></label>
		  </div>
	    </div>
	  </div>
	  <div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required name="training_type" id="training_type" value="<?php echo isset($trainings['training_type'])? $trainings['training_type']:"" ?>"/>		   
		    <label for="training_type">Type of Learning and Development<font size="-1"> (Managerial/Supervisory/Technical/etc.)</font> <span class="required">*</span></label>
		  </div>
	    </div>
	</div>
<!-- NCOCAMPO : Training Type change to Type of LD (Managerial/Supervisory/Technical/etc.) : END -->

	<div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">
		   	<input type="text" class="validate" required name="training_conducted_by" id="training_conducted_by" value="<?php echo isset($trainings['training_conducted_by'])? $trainings['training_conducted_by']:"" ?>"/>
		    <label for="training_conducted_by">Conducted/Sponsored By<span class="required">*</span></label>
		  </div>
	    </div>
	</div>
		<div class='row switch p-md b-b-n'>
			Relevant<br><br>
			<label>
				No
				<input name='relevance_flag' type='checkbox' value='Y' <?php echo ($trainings['relevance_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				<span class='lever'></span>Yes
			</label>
		</div>
	</div>	
</form>
<?php if($action != ACTION_VIEW):?>
<div class="md-footer default">
	<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
    <button class="btn btn-success " id="save_trainings" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
</div>
<?php endif;?>
<script>
$(function (){
	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_trainings').parsley();
	jQuery(document).off('click', '#save_trainings');
	jQuery(document).on('click', '#save_trainings', function(e){	
		$("#form_trainings").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_trainings');
	jQuery(document).on('submit', '#form_trainings', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_trainings').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_trainings';
			<?php else: ?>
				process_url = $base_url + 'main/pds_trainings_info/process';
			<?php endif; ?>
		  	button_loader('save_trainings', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_trainings.closeModal();
							load_datatable('pds_trainings_table', '<?php echo PROJECT_MAIN ?>/pds_trainings_info/get_trainings_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_trainings', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>
