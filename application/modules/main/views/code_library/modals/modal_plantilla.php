<form id="plantilla_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">
	
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<input type="text" class="validate" required name="plantilla_code" id="plantilla_code" value="<?php echo !EMPTY($plantilla_info['plantilla_code']) ? $plantilla_info['plantilla_code'] : ''; ?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
					<label class="<?php echo $action == ACTION_EDIT ? 'active' :'' ?>" for="title">Item number<span class="required">*</span></label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label for="parent_plantilla_id" class="active">Plantilla Parent</label>
					<select id="parent_plantilla_id" name="parent_plantilla_id" class="selectize" placeholder="Select Plantilla Parent" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Plantilla Parent</option>
						<?php if (!EMPTY($parents)): ?>
							<?php foreach ($parents as $parent): ?>
								<option value="<?php echo $parent['plantilla_id'] ?>"><?php echo $parent['plantilla_code'] ?>/<?php echo $parent['position_name'] ?>/<?php echo $parent['name'] ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="office" class="active">Office
						<span class="required">*</span>
					</label> 
					<select id="office"
						name="office"
						class="selectize"
						placeholder="Select Office" required
						<?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
						<option value="" selected></option>
					</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="division" class="active">Division
					</label> 
					<select id="division"
						name="division"
						class="selectize"
						placeholder="Select Division" 
						<?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
						<option value="" selected></option>
					</select>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="position_id" class="active">Position<span class="required">*</span></label>
					<select id="position_id" name="position_id" class="selectize" placeholder="Select Position" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Position</option>
						<?php if (!EMPTY($positions)): ?>
						<?php foreach ($positions as $pos): ?>
							<option value="<?php echo $pos['position_id'] ?>"><?php echo $pos['position_name'] ?></option>
						<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="plantilla_level_name" class="active">Level<span class="required">*</span></label>
					<select id="plantilla_level_name" name="plantilla_level_name" class="selectize" placeholder="Select Level" required <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
						<option value="">Select Level</option>
						<?php if (!EMPTY($plantilla_level)): ?>
						<?php foreach ($plantilla_level as $pl): ?>
							<option value="<?php echo $pl['plantilla_level_id'] ?>"><?php echo $pl['plantilla_level_name'] ?></option>
						<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row m-n">
		    <div class="col s12">
			  <div class="input-field" class="active">
			  	<label class="active" for="general_function">General Function</label>
				<textarea type="text" name="general_function" id="general_function" class="materialize-textarea"><?php echo !EMPTY($plantilla_info['general_function']) ? $plantilla_info['general_function'] : NULL?></textarea>				
		      </div>
		    </div>
	  	</div>
	  	<div class="row m-n">
		    <div class="col s12">
			  <div class="input-field">
			  	<input type="hidden" name="position_duty_id" id="position_duty_id" value="<?php echo !EMPTY($plantilla_info['position_duty_id']) ? $plantilla_info['position_duty_id'] : NULL?>">
			  	<label for="duties">Duties</label>
			  	<br><br>
				<textarea type="text" name="duties" id="duties" class="materialize-textarea"><?php echo !EMPTY($plantilla_info['duties']) ? $plantilla_info['duties'] : NULL?></textarea>				
		      </div>
		    </div>
	  	</div>
		<div class='row switch p-md b-b-n'>
			<label>Active Flag<br><br></label>
		    <label>
		        Inactive
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo ($plantilla_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
		<div class='col s6 switch p-md b-b-n'>
			<label>With RATA<br><br></label>
				<label>
					No
					<input name='rata' type='checkbox' value='Y' <?php echo ($plantilla_info['rata'] == "Y") ? "checked" : "" ?>  <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
					<span class='lever'></span>Yes
				</label>
			</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_plantilla" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>
<script>
$(function (){
	CKEDITOR.replace('duties');
	$('#plantilla_form').parsley();
	$('#plantilla_form').submit(function(e) {
	    e.preventDefault();

	    $('#duties').val(CKEDITOR.instances['duties'].getData());

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_plantilla', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_hr/plantilla/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_plantilla.closeModal();
							load_datatable('table_plantilla', '<?php echo PROJECT_MAIN ?>/code_library_hr/plantilla/get_plantilla_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
					},
					complete : function(jqXHR){
						button_loader('save_plantilla', 0);
					}
			};
			General.ajax(option);    
	    }
  	});
  	
  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>

  	$('#plantilla_code').on('keyup', function(){
  		this.value = this.value.toUpperCase();
  	});

	<?php if(!isset($plantilla_info['general_function']) && !isset($plantilla_info['duties'])) {?>
	  	$('#position_id').on('change', function() {
				position_id = $(this).val();
				<?php foreach($positions as $pos):?>
					if(position_id == <?php echo $pos['position_id']?>) {
						var data = { position_id : position_id };
						// $('#general_function').val("<?php //echo $pos['general_function']?>");

						//marvin : allow new line in plantilla general function
						$('#general_function').val(`"<?php echo $pos['general_function']?>"`);

						$.post( $base_url + 'main/code_library_hr/plantilla/get_position_duty', data, function( result ) {
							CKEDITOR.instances['duties'].setData(result.duty)
						},'json' );
					}
				<?php endforeach;?>
	  	});
	<?php }?>
//ncocampo
//office
	var result = '<option value="">Select Office</option>';
	var offices_value = <?php echo json_encode($offices) ?>;
	var office_code = <?php echo json_encode($offices['office_id']) ?>;


	for(var i=0 ; i < offices_value.length; i++)
	{
		if(offices_value[i]['office_id'] == office_code)
		{
			result += '<option value="' + offices_value[i]['office_id'] + '" selected>' + offices_value[i]['name'] + '</option>';
		}
		else
		{
			result += '<option value="' + offices_value[i]['office_id'] + '">' + offices_value[i]['name'] + '</option>';
		}				
	}
	$('#office').html(result);

//division
	var result = '<option value="">Select Division</option>';

	var division_value = <?php echo json_encode($divisions) ?>;
	var division_code = <?php echo json_encode($divisions['office_id']) ?>;
	for(var i=0 ; i < division_value.length; i++)
	{
		if(division_value[i]['parent_id'] == office_code)
		{
			if(division_value[i]['office_id'] == division_code)
			{
				result += '<option value="' + division_value[i]['office_id'] + '" selected>' + division_value[i]['name'] + '</option>';
			}
			else
			{
				result += '<option value="' + division_value[i]['office_id'] + '">' + division_value[i]['name'] + '</option>';
			}	
		}			
	}
	$('#division').html(result);

//onchange
		$("#office").on("change", function(){
		$('#division')[0].selectize.destroy();
			
		var result = '<option value="">Select Division</option>';
		var divisions_value = <?php echo json_encode($divisions) ?>;
		var office_code = $(this).val();
		for(var i=0 ; i < divisions_value.length; i++)
		{
			if(office_code && divisions_value[i]['parent_id'] === office_code) {
				result += '<option value="' + divisions_value[i]['office_id'] + '">' + divisions_value[i]['name'] + '</option>';
			} else if(!office_code && !divisions_value[i]['parent_id']) {
				result += '<option value="' + divisions_value[i]['office_id'] + '">' + divisions_value[i]['name'] + '</option>';
			}
		}
		$('#division').html(result);
		$('#division').selectize();
	});
// //ncaompo 03/15/2024
})

</script>