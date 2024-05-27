<form id="form_eligibility">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<div class="form-float-label">
	  <div class="row m-n">
	    <div class="col s7">
		  <div class="input-field">
		    <label for="eligibility_type_id" class="active">Eligibility<span class="required">*</span></label>
			<select id="eligibility_type_id" required name="eligibility_type_id" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?> class="selectize" placeholder="Select Eligibility">
				<option value="">Select Eligibility </option>
				<?php if( ! empty($eligibility_types) ): ?>
                	<?php foreach($eligibility_types as $type):                                 
	                    if( ! empty($eligibility) )
	                        $selected = ($type['eligibility_type_id'] == $eligibility['eligibility_type_id']) ? 'selected' : '';
	                    else
	                        $selected = '';
	                ?>
                		<option value="<?php echo $type['eligibility_type_id'].'|'.$type['eligibility_type_flag'] ?>"<?php echo $selected ?>><?php echo $type['eligibility_type_name'] ?></option>
                	<?php endforeach;?>
            	<?php endif;?>
			</select>
	      </div>
	    </div>
	    <?php 
	    $str = isset($eligibility['rating'])? $eligibility['rating']: NOT_APPLICABLE;
	    $rating = ($action != ACTION_ADD) ? $str : ''; ?>
	    <!-- <div class="col s5">
		  <div class="input-field">
		   	<input type="text" class="validate" name="rating" id="rating" min="0" max="100" value="<?php //echo $rating; ?>" <?php //echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="rating">Rating<span class="required">*</span></label>		   
		  </div>
	    </div> -->
		<!-- //NCOCAMPO:ADDED ID IN LABEL :START  -->
	     <div class="col s5">
		  <div class="input-field">
		   	<input type="text" class="validate" name="rating" id="rating" min="0" max="100" value="<?php echo $rating; ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="rating">Rating<span class="required" id="label_rating">*</span></label>		   
		  </div>
	    </div>
		<!-- //NCOCAMPO:ADDED ID IN LABEL:END-->
	  </div>
	  <div class="row m-n">
	    <div class="col s6">
		  <div class="input-field">
		   	<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD" required name="exam_date" id="exam_date"  
				   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'exam_date')"
		   		   value="<?php echo isset($eligibility['exam_date'])? format_date($eligibility['exam_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="exam_date" class="active">Date of Examination/Conferment<span class="required">*</span></label>
		  </div>
	    </div>
	   <div class="col s6">
		  <div class="input-field">
		   	<input type="text" class="validate datepicker" placeholder="YYYY/MM/DD"  name="release_date" id="release_date"  
				   onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'release_date')"
		   		   value="<?php echo isset($eligibility['release_date'])? format_date($eligibility['release_date']):"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
        	<label for="release_date" class="active">Date of Validity <span id="release_date_label" class="required"></span></label>
		  </div>
	    </div>
	</div>
	<div class="row m-n">
		<div class="col s12">
			<div class="input-field">
				<input type="text" class="validate" required name="exam_place" id="exam_place"  value="<?php echo isset($eligibility['exam_place'])? $eligibility['exam_place']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
				<label for="exam_place">Place of Examination/Conferment<span class="required">*</span></label>
			</div>
	    </div>
	</div>
	<div class="row m-n">
	    <div class="col s12">
		  <div class="input-field">



		   	<input type="text" class="validate" required name="license_no" id="license_no" value="<?php echo isset($eligibility['license_no'])? $eligibility['license_no']:"" ?>" <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
		    <label for="license_number">License Number<span id="license_no_label" class="required"></span></label>


		  </div>
	    </div>
	</div>
	<div class='row switch p-md b-b-n'>
		Relevant<br><br>
		<label>
			No
			<input name='relevance_flag' type='checkbox' value='Y' <?php echo ($eligibility['relevance_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
			<span class='lever'></span>Yes
		</label>
	</div>	
	</div>	
	<?php if($action != ACTION_VIEW):?>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button class="btn btn-success " type="button" id="save_eligibility" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	 </div>
	<?php endif;?>
</form>
<script>
$(function (){
	$('#eligibility_type_id').on( "change", function() {
		var selected = $(this).val();
		var fields = selected.split('|');

		var id = fields[0];
		var flag = fields[1];


		if ( flag == '<?php echo ELIGIBILITY_TYPE_FLAG_RA ?>' )
		{
			$('#release_date').prop('required', true);
			$('#release_date_label').html('*');
			$('#license_no').prop('required', true);
			$('#license_no_label').html('*');

		//NCOCAMPO:ADDED ANOTHER ELSE IF FOR CSPD FLAG ->FOR ADDING:START
			$('#rating').prop('required', true);
			$('#label_rating').html('*');
		}
		else if ( flag == '<?php echo ELIGIBILITY_TYPE_FLAG_CSPD ?>' )
		{
			$('#rating').prop('required', false);
			$('#label_rating').html('');
			$('#license_no').prop('required', false);
			$('#license_no_label').html('');
		}
		//NCOCAMPO:ADDED ANOTHER ELSE IF FOR CSPD FLAG ->FOR ADDING:END
		else
		{
			$('#release_date').prop('required', false);
			$('#release_date_label').html('');
			$('#license_no').prop('required', false);
			$('#license_no_label').html('');
			
		}
	});


	var selected = $('#eligibility_type_id').val();
	var fields = selected.split('|');

	var id = fields[0];
	var flag = fields[1];

	if ( flag == '<?php echo ELIGIBILITY_TYPE_FLAG_RA ?>' )
	{
		$('#release_date').prop('required', true);
		$('#release_date_label').html('*');
		$('#license_no').prop('required', true);
		$('#license_no_label').html('*');	

	//NCOCAMPO:ADDED ANOTHER ELSE IF FOR CSPD FLAG ->FOR EDITING:START
		$('#rating').prop('required', true);
		$('#label_rating').html('*');	
	}
	else if ( flag == '<?php echo ELIGIBILITY_TYPE_FLAG_CSPD ?>' )
		{
			$('#rating').prop('required', false);
			$('#label_rating').html('');
			$('#license_no').prop('required', false);
			$('#license_no_label').html('');
		}
	//NCOCAMPO:ADDED ANOTHER ELSE IF FOR CSPD FLAG ->FOR EDITING:END
	else
	{
		$('#release_date').prop('required', false);
		$('#release_date_label').html('');
		$('#license_no').prop('required', false);
		$('#license_no_label').html('');
	}

	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
	$('#form_eligibility').parsley();
	jQuery(document).off('click', '#save_eligibility');
	jQuery(document).on('click', '#save_eligibility', function(e){	
		$("#form_eligibility").trigger('submit');
	});

 	jQuery(document).off('submit', '#form_eligibility');
	jQuery(document).on('submit', '#form_eligibility', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#form_eligibility').serialize();
			var process_url = "";
			<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_government_exam';
			<?php else: ?>
				process_url = $base_url + 'main/pds_government_exam_info/process';
			<?php endif; ?>
		  	button_loader('save_eligibility', 1);
		  	var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_government_exam.closeModal();
							load_datatable('pds_government_exam_table', '<?php echo PROJECT_MAIN ?>/pds_government_exam_info/get_government_exam_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_eligibility', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>