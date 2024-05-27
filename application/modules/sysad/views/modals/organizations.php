<?php
$id                         = (ISSET($id)) ? $id : '';
$salt                       = (ISSET($salt)) ? $salt : '';
$token                      = (ISSET($token)) ? $token : '';
$org_code                   = (ISSET($org_details['org_code']) && ! EMPTY($org_details['org_code'])) ? $org_details['org_code'] : '';
$disabled                   = (ISSET($org_details['org_code']) && ! EMPTY($org_details['org_code'])) ? 'disabled' : '';
$org_name                   = (ISSET($org_details['name']) && ! EMPTY($org_details['name'])) ? $org_details['name'] : '';
$website                    = (ISSET($org_details['website']) && ! EMPTY($org_details['website'])) ? $org_details['website'] : '';
$email                      = (ISSET($org_details['email']) && ! EMPTY($org_details['email'])) ? $org_details['email'] : '';
$phone                      = (ISSET($org_details['phone']) && ! EMPTY($org_details['phone'])) ? $org_details['phone'] : '';
$fax                        = (ISSET($org_details['fax']) && ! EMPTY($org_details['fax'])) ? $org_details['fax'] : '';
$org_parent_code            = (ISSET($org_details['org_parent']) && ! EMPTY($org_details['org_parent'])) ? $org_details['org_parent'] : '';
$org_short_name             = (ISSET($org_details['short_name']) && ! EMPTY($org_details['short_name'])) ? $org_details['short_name'] : '';
$responsibility_center_code = (ISSET($org_details['responsibility_center_code']) && ! EMPTY($org_details['responsibility_center_code'])) ? $org_details['responsibility_center_code'] : '';
$office_type_id             = (ISSET($org_details['office_type_id']) && ! EMPTY($org_details['office_type_id'])) ? $org_details['office_type_id'] : '';
$active_flag             	= (ISSET($org_details['active_flag']) && ! EMPTY($org_details['active_flag'])) ? $org_details['active_flag'] : '';

?>
<form id="organization_form">
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"/>
	<input type="hidden" name="salt"  id="salt" value="<?php echo $salt; ?>"/>
	<input type="hidden" name="token" id="token" value="<?php echo $token; ?>"/>
	
	<div class="form-float-label">
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					
					<input type="text" data-parsley-required="true" name="org_code"  id="org_code" value="<?php echo $org_code; ?>" <?php echo $disabled ?>  />
					<label for="org_code" class="active">Organization Code <span class="required"> *</span></label> 
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="org_short_name" class="active">Organization Short Name <span class="required"> *</span></label>
					<input type="text" data-parsley-required="true"  name="org_short_name"  id="org_short_name" value="<?php echo $org_short_name; ?>" /> 
			  	</div>
			</div>
		</div>	
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="org_name" class="active">Organization Name <span class="required"> *</span></label>
					<input type="text" data-parsley-required="true" name="org_name"  id="org_name" value="<?php echo $org_name; ?>"  /> 
				</div>
			</div>
		</div>			
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="parent_org_code" class="active">Parent Organization</label>
					<select name="parent_org_code" id="parent_org_code" class="selectize"  placeholder="Select sector">
								<option value=""></option>
							<?php foreach($other_orgs as $key => $val): ?>
								<option value="<?php echo $val['value']; ?>"><?php echo $val['text']; ?></option>
							<?php endforeach; ?>
					</select> 
			  	</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="office_type" class="active">Office Type <span class="required"> *</span></label>
					<select name="office_type" id="office_type" data-parsley-required="true" class="selectize"  placeholder="Select Type">
						<option value=""></option>
						<?php foreach($office_type as $key => $val): ?>
							<option value="<?php echo $val['office_type_id']; ?>"><?php echo $val['office_type_name']; ?></option>
						<?php endforeach; ?>
					</select> 
			  	</div>
			</div>
		</div>
		<div class="row m-n">
			<div class="col s12">
				<div class="input-field">
					<label for="responsibility_center_code" class="active">Responsibility Center Code <span class="required"> *</span></label>
					<select name="responsibility_center_code" id="responsibility_center_code" data-parsley-required="true" class="selectize"  placeholder="Select Responsibility Center Code ">
						<option value=""></option>
						<?php foreach($rcc_list as $key => $val): ?>
							<option value="<?php echo $val['responsibility_center_code']; ?>" <?php echo ($val['responsibility_center_code'] == $responsibility_center_code) ? ' selected ' : ''; ?>><?php echo $val['responsibility_center_desc']; ?></option>
						<?php endforeach; ?>
					</select> 
				</div>
			</div>
		</div>			
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="website" class="active">Website</label>
					<input type="text" name="website"  id="website" value="<?php echo $website; ?>"  /> 
			  	</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="email" class="active">Email</label>
					<input type="email" name="email" id="email" value="<?php echo $email; ?>"  /> 
			  	</div>
			</div>
		</div>				
		<div class="row m-n">
			<div class="col s6">
				<div class="input-field">
					<label for="tel_no" class="active">Telephone No. <span class="required"> *</span></label>
					<input type="text" data-parsley-type="integer" data-parsley-required="true" name="tel_no"  id="tel_no" value="<?php echo $phone; ?>"  /> 
			  	</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="fax_no" class="active">Fax No.</label>
					<input type="text" data-parsley-type="integer" name="fax_no"  id="fax_no" value="<?php echo $fax; ?>"  /> 
			  	</div>
			</div>
		</div>		
		<div class='row switch p-md b-b-n'>
			Active Flag<br><br>
			<label>
				No
				<input name='active_flag' type='checkbox' value='Y' <?php echo ($active_flag == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				<span class='lever'></span>Yes
			</label>
		</div>	
	</div>
<!-- end of form-float-label -->
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat" id="cancel_organization">Cancel</a>
		<button type="submit" class="btn " id="save_organization" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
  	</div>
<!-- end of md-footer -->  	
</form>

<script>
$(function(){
	$('.selectize').not(".filter").selectize();
	var $org     = $("#parent_org_code")[0].selectize;
		$parsley = $("#organization_form").parsley();
		$org.clear();

	<?php if( ISSET($org_parent_code)) : ?>
		$("#parent_org_code")[0].selectize.setValue('<?php echo $org_parent_code; ?>');
	<?php endif; ?>	
	<?php if( ISSET($office_type_id)) : ?>
		$("#office_type")[0].selectize.setValue('<?php echo $office_type_id; ?>');
	<?php endif; ?>	
		
	$('#organization_form').on('submit', function(ev){
		ev.preventDefault();
		
		$parsley.validate();
		
		if($parsley.isValid()){
			button_loader('save_organization', 1);

			var form_data = $(this).serialize();
			$.ajax({
				url      : '<?php echo base_url() . PROJECT_CORE ?>/organizations/save',
				data     : form_data,		
				dataType : 'json',
				method   : 'POST',
				success  : function(response){
					var notif_type = (response.status) ?  "<?php echo SUCCESS ?>" : "<?php echo ERROR ?>";

					notification_msg(notif_type, response.msg);
					
					if(response.status){
						modalObj.closeModal();
					    load_datatable(response.table_id, response.path,false,0,0,true);
					}

					button_loader("save_organization",0);
				},
				error   : function(jqXHR, textStatus, errorThrown){
					console.log('Error : '+textStatus);
				}
			}); 
			
		}	
		
	});
		
});
</script>