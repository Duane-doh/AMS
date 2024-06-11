<?php
// IDENTIFICATION INFORMATION
$tin_val = '';
$tin_format = '';
$sss_val = '';
$sss_format = '';
$gsis_val = '';
$gsis_format = '';
$pagibig_val = '';
$pagibig_format = '';
$philhealth_val = '';
$philhealth_format = '';
$permanent_no = '';
$email = '';
$residential_no = '';
$mobile_no = '';
// CONTACTS VALUE
foreach ($contact_info as $contact) {
	switch ($contact['contact_type_id']) {
		case PERMANENT_NUMBER:
			$permanent_no 	= $contact['contact_value'];	
		break;
		case EMAIL:
			$email 			= $contact['contact_value'];	
		break;
		case RESIDENTIAL_NUMBER:
			$residential_no = $contact['contact_value'];	
		break;
		case MOBILE_NUMBER:
			$mobile_no 		= $contact['contact_value'];	
		break;
	}
}
// IDENTIFICATION VALUE
foreach ($identification_info as $identification) {
	switch ($identification['identification_type_id']) {
		case TIN_TYPE_ID:
			$tin_val 		= $identification['identification_value'];	
		break;
		case SSS_TYPE_ID:
			$sss_val 		= $identification['identification_value'];	
		break;
		case GSIS_TYPE_ID:
			$gsis_val 		= $identification['identification_value'];	
		break;
		case PAGIBIG_TYPE_ID:
			$pagibig_val 	= $identification['identification_value'];	
		break;
		case PHILHEALTH_TYPE_ID:
			$philhealth_val = $identification['identification_value'];	
		break;
	}
}
// IDENTIFICATION FORMAT
foreach ($identification_format as $format) {
	switch ($format['identification_type_id']) {
		case TIN_TYPE_ID:		
			$tin_format 		= $format['format'];
		break;
		case SSS_TYPE_ID:	
			$sss_format 		= $format['format'];
		break;
		case GSIS_TYPE_ID:
			$gsis_format 		= $format['format'];
		break;
		case PAGIBIG_TYPE_ID:
			$pagibig_format 	= $format['format'];
		break;
		case PHILHEALTH_TYPE_ID:	
			$philhealth_format 	= $format['format'];
		break;
	}
}
?>
<form id="form_personal_info" class="m-b-md m-t-n-xxs" autocomplete="off">
	<input type="hidden" name="id" value="<?php echo $id ?>" /> 
	<input type="hidden" name="salt" value="<?php echo $salt ?>" /> 
	<input type="hidden" name="token" value="<?php echo $token ?>" /> 
	<input type="hidden" name="action" value="<?php echo $action ?>" /> 
	<input type="hidden" name="module" value="<?php echo $module ?>" /> 
	<input type="hidden" name="proceed_flag" id="proceed_flag" value="<?php echo ($action == ACTION_ADD) ? 'N':'Y'?>" />
	<input type="hidden" name="job_order_flag" id="jo_flag" value="<?php echo (!EMPTY($personal_info['job_order_flag']) AND $personal_info['job_order_flag'] == 'Y')? '1' : '' ?>" />

	<div class="card m-b-n">
		<div class="card-image p-r-md">
			<div class="">
				<div class="form-float-label">
				<?php if ($module == MODULE_PERSONNEL_PORTAL) { ?>
					<p>Note: The updating of <span style="color:red"><strong>restricted</strong></span> fields must be coordinated with the Personnel Administration Division.</p>
				<?php } ?>
					<div class="row b-t b-light-gray">
						<div class="col s12">
							<div class="input-field">
								<input tabindex="1" id="last_name" name="last_name" required
									value="<?php echo !EMPTY($personal_info['last_name']) ? ucwords($personal_info['last_name']) : '' ?>"
									type="text" class="validate"> <label for="last_name">Last Name
									<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">*</span>';
									}?>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col 12">
							<div class="input-field">
								<input tabindex="2" id="first_name" name="first_name" required
									value="<?php echo !EMPTY($personal_info['first_name'])? ucwords($personal_info['first_name']) : '' ?>"
									type="text" class="validate"> <label for="first_name">First
									Name 
									<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">*</span>';
									}?>
								</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<input tabindex="3" id="middle_name" name="middle_name" required
									value="<?php echo !EMPTY($personal_info['middle_name']) ? ucwords($personal_info['middle_name']) : '' ?>"
									type="text" class="validate"> <label for="middle_name">Middle
									Name 
									<?php if($module == MODULE_PERSONNEL_PORTAL){
											echo '<span class="required">(Restricted)</span>';
										}else{
											echo '<span class="required">*</span>';
									}?>
								</label>
							</div>
						</div>
						<div class="col s4">
							<div class="input-field">
								<label for="ext_name" class="active">Extension Name</label> <select
									tabindex="4" name="ext_name" id="ext_name"
									class="selectize validate" placeholder="Select Extension Name">
									<option value="">Select Extension Name</option>
									<option value="JR.">JR.</option>
									<option value="II">II</option>
									<option value="III">III</option>
									<option value="IV">IV</option>
									<option value="V">V</option>
									<option value="VI">VI</option>
									<option value="VII">VII</option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col s6">
							<div class="input-field">
								<input disabled tabindex="30" id="biometric_pin"
									name="biometric_pin" 
									value="<?php echo !EMPTY($personal_info['biometric_pin'])  ? $personal_info['biometric_pin'] : '' ?>"
									type="text" class="validate"> <label for="biometric_pin"
									class="active">Biometric Pin</label>
							</div>
						</div>
						<div class="col s4 b-r-n">
							<div class="input-field">
								<input tabindex="39" id="agency_employee_id" <?php echo ($module == MODULE_PERSONNEL_PORTAL) ? 'readonly' : '' ?>
									data-parsley-length="[8, 8]" name="agency_employee_id" 
									value="<?php echo !EMPTY($personal_info['agency_employee_id']) ? $personal_info['agency_employee_id'] :  ''?>"
									type="text" class="validate"> <label for="agency_employee_id"
									class="active">Agency Employee Number <span class="">*</span></label>
							</div>
						</div>
						<?php
							$style = ($action == ACTION_ADD) ? 'margin-top: -2px' : 'margin-top: 30px';							
						?>
						<?php if($action == ACTION_VIEW OR $module == MODULE_PERSONNEL_PORTAL) : ?>
							<div class="col s2 p-t-md p-l-lg">
							</div>
						<?php else: ?>
							<div class="col s2 p-t-md p-l-lg">
								<span class="font-md">Generate</span>
								<div class="input-field p-l-md" style="<?php echo $style?>"> 													
									<input name="generate_agency_number" id="generate_agency_number" class="filled-in" type="checkbox">
									<label for="generate_agency_number"></label>							  		
							  	</div>
							</div>
						<?php endif; ?>
					</div>
						
					</div>
				</div>
			</div>
		</div>

		
	<div class="right-align p-r-sm p-t-sm">
		<?php if ($action != ACTION_VIEW): ?>
				<button class="btn btn-success" id="save_personal_info"
			type="button" name="action" value="Save"  tabindex="40" >Save</button>
		<?php endif; ?>
	</div>
</form>
<script type="text/javascript">
$("#citizenships").change(function() {
    var citizenships = $(this).val();
    if (citizenships == <?php echo CITIZENSHIP_FILIPINO; ?>) 
    {
    	$('#required_citi').empty('');
       	$('#citizenship_basis').prop('required', false);
		$('#citizenship_basis').attr('disabled', true);
		$('#citizenship_basis')[0].selectize.destroy();
		$('#citizenship_basis').attr("disabled", true);
		$('#citizenship_basis').selectize();
		$("#citizenship_basis")[0].selectize.clear();
    } 
    else 
    {
    	$('#required_citi').html('*');
       	$('#citizenship_basis').prop('required', true);
		$('#citizenship_basis')[0].selectize.destroy();
		$('#citizenship_basis').attr("disabled", false);
		$('#citizenship_basis').selectize();
		$("#citizenship_basis")[0].selectize.clear();
    }
});


	<?php if($personal_info['height'] == 'Y'): ?>
		$(document).ready(function() {
			$('#jo_flag').val(1);
		});
	<?php endif; ?>
	<?php if($lacking_fields): ?>
		$(document).ready(function() {
			$(".activator").trigger("click");
		});
	<?php endif; ?>

	$(function(){
			
			var result = '<option value="">Select Region</option>';
			var regions_value = <?php echo json_encode($regions_values) ?>;
			
			var region = <?php echo json_encode($permanent_address_info['region_code']) ?>;
			for(var i=0 ; i < regions_value.length; i++)
			{
				if(regions_value[i]['region_code'] == region)
				{
					result += '<option value="' + regions_value[i]['region_code'] + '" selected>' + regions_value[i]['region_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + regions_value[i]['region_code'] + '">' + regions_value[i]['region_name'] + '</option>';
				}				
			}
			$('#region_permanent').html(result);


			var result = '<option value="">Select Province</option>';
			var provinces_value = <?php echo json_encode($provinces_value) ?>;
			var province = <?php echo json_encode($permanent_address_info['province_code']) ?>;
			for(var i=0 ; i < provinces_value.length; i++)
			{
				if(provinces_value[i]['province_code'] == province)
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected> METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected>' + provinces_value[i]['province_name'] + '</option>';
					}
				}
				else
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
					}
				}				
			}
			$('#province_permanent').html(result);

			var result = '<option value="">Select Municipality</option>';
			
			var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
			var municity = <?php echo json_encode($permanent_address_info['municity_code']) ?>;
			for(var i=0 ; i < municipalities_value.length; i++)
			{
				if(municipalities_value[i]['province_code'] == province)
				{
					if(province == '13806')
					{
						if(municipalities_value[i]['municity_code'] !== '1380600')
						{
							if(municipalities_value[i]['municity_code'] == municity)
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
								
							}
							else
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
							}	
						}
					}
					else
					{
						if(municipalities_value[i]['municity_code'] == municity)
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected>' + municipalities_value[i]['municity_name'] + '</option>';
							
						}
						else
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
						}	
					}			
				}		
			}
			$('#municipalities_permanent').html(result);
			
			var result = '<option value="" selected>Select Barangay</option>';
			
			var barangays_value = <?php echo json_encode($barangays_value) ?>;
			var barangay = <?php echo json_encode($permanent_address_info['barangay_code']) ?>;
			for(var i=0 ; i < barangays_value.length; i++)
			{
				if(barangays_value[i]['municity_code'] == municity)
				{
					if(barangays_value[i]['barangay_code'] == barangay)
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '" selected>' + barangays_value[i]['barangay_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
					}	
				}			
			}
			$('#barangay_permanent').html(result);


			var result = '<option value="">Select Region</option>';
			var regions_value = <?php echo json_encode($regions_values) ?>;
			
			var region = <?php echo json_encode($residential_address_info['region_code']) ?>;
			for(var i=0 ; i < regions_value.length; i++)
			{
				if(regions_value[i]['region_code'] == region)
				{
					result += '<option value="' + regions_value[i]['region_code'] + '" selected>' + regions_value[i]['region_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + regions_value[i]['region_code'] + '">' + regions_value[i]['region_name'] + '</option>';
				}				
			}
			$('#region_residential').html(result);

			var result = '<option value="">Select Province</option>';
			var provinces_value = <?php echo json_encode($provinces_value) ?>;
			var province = <?php echo json_encode($residential_address_info['province_code']) ?>;
			for(var i=0 ; i < provinces_value.length; i++)
			{
				if(provinces_value[i]['province_code'] == province)
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected> METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '" selected>' + provinces_value[i]['province_name'] + '</option>';
					}
				}
				else
				{
					if(region == '13')
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
					}
				}				
			}
			$('#province_residential').html(result);

			var result = '<option value="">Select Municipality</option>';
			
			var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
			var municity = <?php echo json_encode($residential_address_info['municity_code']) ?>;
			for(var i=0 ; i < municipalities_value.length; i++)
			{
				if(municipalities_value[i]['province_code'] == province)
				{
					if(province == '13806')
					{
						if(municipalities_value[i]['municity_code'] !== '1380600')
						{
							if(municipalities_value[i]['municity_code'] == municity)
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
								
							}
							else
							{
								result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila -' + municipalities_value[i]['municity_name'] + '</option>';
							}	
						}
					}
					else
					{
						if(municipalities_value[i]['municity_code'] == municity)
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '" selected>' + municipalities_value[i]['municity_name'] + '</option>';
							
						}
						else
						{
							result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
						}	
					}			
				}			
			}
			$('#municipalities_residential').html(result);
			
			var result = '<option value="" selected>Select Barangay</option>';
			
			var barangays_value = <?php echo json_encode($barangays_value) ?>;
			var barangay = <?php echo json_encode($residential_address_info['barangay_code']) ?>;
			for(var i=0 ; i < barangays_value.length; i++)
			{
				if(barangays_value[i]['municity_code'] == municity)
				{
					if(barangays_value[i]['barangay_code'] == barangay)
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '" selected>' + barangays_value[i]['barangay_name'] + '</option>';
					}
					else
					{
						result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
					}	
				}			
			}
			$('#barangay_residential').html(result);

		
		// REPLACATION OF AGENCY EMPLOYEE ID VALUE TO BIOMETRIC PIN
		var $src = $('#agency_employee_id'),
	        $dst = $('#biometric_pin');
	    $src.on('input', function () {
	        $dst.val($src.val());
	    });

		<?php if($action != ACTION_ADD): ?>
			$("label:not(.gender)").addClass('active');
		
		<?php endif; ?>	

		jQuery(document).off('click', '#save_personal_info');
		jQuery(document).on('click', '#save_personal_info', function(e){	
			$("#form_personal_info").trigger('submit');
		});
		$('#form_personal_info').parsley();

		jQuery(document).off('submit', '#form_personal_info');
		jQuery(document).on('submit', '#form_personal_info', function(e){
			e.preventDefault();
			if ( $(this).parsley().isValid() ) {

				var data = $('#form_personal_info').serialize();
				data += '&biometric_pin=' + $('#agency_employee_id').val();
				var process_url = "";
				<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_peronal_info';
				<?php else: ?>
				process_url = $base_url + 'main/pds_personal_info/process';
				<?php endif; ?>
				button_loader('save_personal_info', 1);
				var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status === true)
						{
							<?php if($module != MODULE_PERSONNEL_PORTAL):?>
							if(result.proceed_flag == 'Y')
							{
								notification_msg("<?php echo SUCCESS ?>", result.message);
								//button_loader("save_personal_info",0);
								var reload_url = result.reload_url;
								if(reload_url != "")
								{
									setTimeout(function(){ window.location.href = "<?php echo base_url() . PROJECT_MAIN ?>/pds/display_pds_info/"+ reload_url; }, 2000);
								}
							}
							else
							{
								confirm_duplicate();
							}
							<?php else: ?>
								notification_msg("<?php echo SUCCESS ?>", result.message);
								//button_loader("save_personal_info",0);
								var reload_url = result.reload_url;
								if(reload_url != "")
								{
									setTimeout(function(){ window.location.href = "<?php echo base_url() . PROJECT_MAIN ?>/pds/display_pds_info/"+ reload_url; }, 2000);
								}
							<?php endif;?>
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
							button_loader('save_personal_info', 0);
						}	

					},

					complete : function(jqXHR){
						//button_loader('save_personal_info', 0);
					}
				};
				General.ajax(option);  		    
			}

		});

	});
	function confirm_duplicate(){
	
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {

			$('#proceed_flag').val('Y');
			$("#form_personal_info").trigger('submit');
		},
		onCancelBut : function() {
			button_loader('save_personal_info', 0);
		},
		onLoad : function() {
			$('.confirmModal_content h4').html('<span class="orange-text">Warning!</span>');	
			$('.confirmModal_content p').html('Employee information already exist. Continue anyway?');
		},
		onClose : function() {}
	});
}
	// GET GENERATED ID
	$("#generate_agency_number").on("change", function(){
		if($(this).is(":checked")) {
			$.post($base_url+"<?php echo PROJECT_MAIN.'/pds_personal_info/get_generated_id'?>", function(result) {		
				$("#agency_employee_id").val(result.generated_id);
				$("#biometric_pin").val(result.generated_id);
				$("#agency_employee_id").attr('readonly', true);
			}, 'json');
		}
		else
		{
			$("#agency_employee_id").val("<?php echo !EMPTY($personal_info['agency_employee_id'])  ? $personal_info['agency_employee_id'] : '' ?>");
			$("#biometric_pin").val("<?php echo !EMPTY($personal_info['biometric_pin'])  ? $personal_info['biometric_pin'] : '' ?>");
			$("#agency_employee_id").attr('readonly', false);
		}
	});

	$("#region_residential").on("change", function(){
		$('#province_residential')[0].selectize.destroy();
			
		var result = '<option value="">Select Province</option>';
		var provinces_value = <?php echo json_encode($provinces_value) ?>;
		var region_code = $(this).val();
		for(var i=0 ; i < provinces_value.length; i++)
		{
			if(provinces_value[i]['region_code'] == region_code)
			{
				if(region_code == '13')
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
				}

			}
		}

			$('#province_residential').html(result);
			$('#province_residential').selectize();


			$('#municipalities_residential')[0].selectize.destroy();
			var result = '<option value="">Select Municipality</option>';
			$('#municipalities_residential').html(result);
			$('#municipalities_residential').selectize();

			$('#barangay_residential')[0].selectize.destroy();
			var result = '<option value="">Select Barangay</option>';
			$('#barangay_residential').html(result);
			$('#barangay_residential').selectize();
	});
	
	$("#province_residential").on("change", function(){
		$('#municipalities_residential')[0].selectize.destroy();
			
		var result = '<option value="">Select Municipality</option>';
		var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
		var province_code = $(this).val();
		for(var i=0 ; i < municipalities_value.length; i++)
		{
			if(municipalities_value[i]['province_code'] == province_code)
			{
				if(province_code == '13806')
				{
					if(municipalities_value[i]['municity_code'] !== '1380600')
					{
						result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila - ' + municipalities_value[i]['municity_name'] + '</option>';
					}
				}
				else
				{
					result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
				}
			}
		}
		$('#municipalities_residential').html(result);
		$('#municipalities_residential').selectize();

		$('#barangay_residential')[0].selectize.destroy();
		var result = '<option value="">Select Barangay</option>';
		$('#barangay_residential').html(result);
		$('#barangay_residential').selectize();
	});

	$("#municipalities_residential").on("change", function(){
		$('#barangay_residential')[0].selectize.destroy();
			
		var result = '<option value="">Select Barangay</option>';
		var barangays_value = <?php echo json_encode($barangays_value) ?>;
		var municity_code = $(this).val();
		for(var i=0 ; i < barangays_value.length; i++)
		{
			if(barangays_value[i]['municity_code'] == municity_code)
			{
				result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
			}
		}
		$('#barangay_residential').html(result);
		$('#barangay_residential').selectize();
	});

	$("#region_permanent").on("change", function(){
		$('#province_permanent')[0].selectize.destroy();
			
		var result = '<option value="">Select Province</option>';
		var provinces_value = <?php echo json_encode($provinces_value) ?>;
		var region_code = $(this).val();
		for(var i=0 ; i < provinces_value.length; i++)
		{
			if(provinces_value[i]['region_code'] == region_code)
			{
				if(region_code == '13')
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">METRO MANILA - ' + provinces_value[i]['province_name'] + '</option>';
				}
				else
				{
					result += '<option value="' + provinces_value[i]['province_code'] + '">' + provinces_value[i]['province_name'] + '</option>';
				}

			}
		}

			$('#province_permanent').html(result);
			$('#province_permanent').selectize();


			$('#municipalities_permanent')[0].selectize.destroy();
			var result = '<option value="">Select Municipality</option>';
			$('#municipalities_permanent').html(result);
			$('#municipalities_permanent').selectize();

			$('#barangay_permanent')[0].selectize.destroy();
			var result = '<option value="">Select Barangay</option>';
			$('#barangay_permanent').html(result);
			$('#barangay_permanent').selectize();
	});
	

	$("#province_permanent").on("change", function(){
		$('#municipalities_permanent')[0].selectize.destroy();
			
		var result = '<option value="">Select Municipality</option>';
		var municipalities_value = <?php echo json_encode($municipalities_value) ?>;
		var province_code = $(this).val();
		for(var i=0 ; i < municipalities_value.length; i++)
		{
			if(municipalities_value[i]['province_code'] == province_code)
			{
				if(province_code == '13806')
				{
					if(municipalities_value[i]['municity_code'] !== '1380600')
					{
						result += '<option value="' + municipalities_value[i]['municity_code'] + '"> City Of Manila - ' + municipalities_value[i]['municity_name'] + '</option>';
					}
				}
				else
				{
					result += '<option value="' + municipalities_value[i]['municity_code'] + '">' + municipalities_value[i]['municity_name'] + '</option>';
				}
			}
		}
		$('#municipalities_permanent').html(result);
		$('#municipalities_permanent').selectize();

		$('#barangay_permanent')[0].selectize.destroy();
		var result = '<option value="">Select Barangay</option>';
		$('#barangay_permanent').html(result);
		$('#barangay_permanent').selectize();
	});

	$("#municipalities_permanent").on("change", function(){
		$('#barangay_permanent')[0].selectize.destroy();
			
		var result = '<option value="">Select Barangay</option>';
		var barangays_value = <?php echo json_encode($barangays_value) ?>;
		var municity_code = $(this).val();
		
		for(var i=0 ; i < barangays_value.length; i++)
		{
			if(barangays_value[i]['municity_code'] == municity_code)
			{
				result += '<option value="' + barangays_value[i]['barangay_code'] + '">' + barangays_value[i]['barangay_name'] + '</option>';
			}
		}
		$('#barangay_permanent').html(result);
		$('#barangay_permanent').selectize();
	});

	// TO ENTIRELY REMOVE THE ASTERISKS(*) AND DISABLE ALL FIELDS IN THIS VIEW
	<?php if($action == ACTION_VIEW) : ?>
		$('span.required').addClass('none');
		$('.validate').attr('disabled','');
	<?php endif; ?>

	<?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
		$('#last_name').prop('readonly', true);
		$('#first_name').prop('readonly', true);
		$('#middle_name').prop('readonly', true);
		$('#cs_readonly').prop('readonly', true);

		
		
	<?php endif; ?>

	
	<?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
		$('#cs_ro_div').css("cssText", "display: table !important");
		$('#civil_stat_div').css("cssText", "display: none !important"); 
	<?php else : ?>
		$('#cs_ro_div').css("cssText", "display: none !important"); 
		$('#civil_stat_div').css("cssText", "display: table !important"); 
	<?php endif; ?>



	$('input:not([type=email]):not([type=password])').keyup(function () {
       $(this).val($(this).val().toUpperCase());
   	});
</script>
