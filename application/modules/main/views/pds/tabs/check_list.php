<form class="m-b-md" id="form_check_list">
	<input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" id="salt" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" id="token" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" id="action" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" id="module" name="module" value="<?php echo $module ?>"/>
	<div class="form-basic">
		<table class="striped table-default">
			<thead class="green white-text">
				<tr>
					<td width = "30%" class="font-semibold">Name</td>
					<td width = "50%" class="font-semibold">Description</td>
				</tr>
			</thead>
			<tbody>
				<?php if(isset($check_list)):?>
					<?php foreach($check_list as $list):?>
						<tr>
							<td>
								<input type="checkbox" name="checklist[]" id="checklist_<?php echo $list['check_list_id']?>" value = "<?php echo isset($list['check_list_id']) ? $list['check_list_id']:''?>" class="ind_checkbox" <?php echo ($action == ACTION_VIEW OR $module == MODULE_PERSONNEL_PORTAL) ? 'disabled':''?> <?php echo in_array($list['check_list_id'],$emp_checklist) ? 'checked':''?> /> <label for="checklist_<?php echo $list['check_list_id']?>"><?php echo isset($list['check_list_name']) ? $list['check_list_name']:''?></label>
							</td>
							<td><?php echo isset($list['check_list_description']) ? $list['check_list_description']:''?></td>
						</tr>
					<?php endforeach;?>
				<?php endif;?>
			</tbody>
		</table>
	</div>
	<div class="p-r-sm p-t-sm">
	<?php if ($action != ACTION_VIEW): ?>
		<button class="btn btn-success" id="save_checklist" type="button" name="save_checklist" value = "Save">Save</button>
	<?php ENDIF; ?>
	</div>
</form>
<script type="text/javascript">
$(function(){
	
	jQuery(document).off('click', '#save_checklist');
	jQuery(document).on('click', '#save_checklist', function(e){
	 
		var data = $('#form_check_list').serialize();
		  button_loader('save_checklist', 1);
	 	$.post($base_url + "main/pds/process_checklist",data, function(result) {
	      	if(result.status){

	        	notification_msg("<?php echo SUCCESS ?>", result.message);
				 button_loader("save_checklist",0);
	        } 
	        else {
	            notification_msg("<?php echo ERROR ?>", result.message);
	             button_loader("save_checklist",0);
	        }
	   }, 'json');
		   
	});
});
</script>