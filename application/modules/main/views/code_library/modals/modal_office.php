<form id="office_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
		 	<div class="col s12">
				<div class="input-field">
					<label for="organization" class="active">Office Name<span class="required">*</span></label>
					<select id="organization" name="organization" class="selectize" placeholder="Select Office">
					 <option value="">Select Office</option>
					 <?php if (!EMPTY($name)): ?>
						<?php foreach ($name as $dt): ?>
							<option value="<?php echo $dt['org_code']?>"><?php echo $dt['name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
		  	<div class="col s12">
				<div class="input-field">
					<label for="cluster" class="active">Cluster<span class="required">*</span></label>
					<select id="cluster" name="cluster" class="selectize" placeholder="Select Cluster">
					 <option value="">Select Cluster</option>
					 <?php if (!EMPTY($cluster_name)): ?>
						<?php foreach ($cluster_name as $dt): ?>
							<option value="<?php echo $dt['cluster_id']?>"><?php echo $dt['cluster_name'] ?></option>
						<?php endforeach;?>
					<?php endif;?>
					</select>
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
		    <label>
		        Inactive
		        <input name='active_flag' type='hidden'  	value='N'>
		        <input name='active_flag' type='checkbox'   value='Y' <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo ($office_info['active_flag'] == "Y") ? "checked" : "" ?>> 
		        <span class='lever'></span>Active
		    </label>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal" id="cancel_office">Cancel</a>
	  <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
		    <button class="btn btn-success " id="save_office" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>
	</div>
</form>
<script>
$(function (){
	$('#office_form').parsley();
	$('#office_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_office', 1);
		  	var option = {
					url  : $base_url + 'main/code_library/process_office',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#cancel_office").trigger('click');
							load_datatable('office_table', '<?php echo PROJECT_MAIN ?>/code_library/get_office_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_office', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})
</script>