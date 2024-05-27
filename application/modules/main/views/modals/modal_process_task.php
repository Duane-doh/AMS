<form id="task_form">
<div class="panel row">
	<div class="row m-md">
			<div class="row m-n">
				<div class="col s2 p-l-n">
					<?php 
						$path = base_url().PATH_USER_UPLOADS.$task_info['photo'];
						if(!file_exists($path))
						{
							$path = base_url().PATH_IMAGES . "avatar.jpg";
						}
					?>
					<img class="circle" width="65" height="65" src="<?php echo $path ?>"/>
				</div>
				<div class="col s10 p-t-sm p-n">
					<div class="row m-n">
						<div class="col s12">
							<label class="dark font-lg">
							<?php //echo isset($task_info['first_name']) ? ucfirst($task_info['first_name']):""?> <?php //echo isset($task_info['last_name']) ? ucfirst($task_info['last_name']):""?></label>
							
							<?php 
								  // ====================== jendaigo : start : change name format ============= //
								  echo isset($task_info['first_name']) ? ucfirst($task_info['first_name']):"";
								  echo (isset($task_info['middle_name']) AND ($task_info['middle_name']!='NA' OR $task_info['middle_name']!='N/A' OR $task_info['middle_name']!='-' OR $task_info['middle_name']!='/')) ? " ".ucfirst(substr($task_info['middle_name'], 0, 1))."." : "";
								  echo isset($task_info['last_name']) ? " ".ucfirst($task_info['last_name']):"";
								  echo isset($task_info['ext_name']) ? " ".ucfirst($task_info['ext_name']):"";
								  // ====================== jendaigo : end : change name format ============= //
							?>
						</div>
					</div>
					<div class="row m-n p-t-sm">
						<div class="col s12">
							<label class="font-md"><?php echo isset($task_info['agency_employee_id']) ? $task_info['agency_employee_id']:""?></label>
						</div>
					</div>
					<div class="row m-n none">
						<div class="col s12">
							<label class="font-sm">Bureau of Quarantine</label>
						</div>
					</div>
				</div>
			</div>
			<hr>
	</div>
	<div class="row m-n m-t-md">
		<div class="col s12">
			<div class="pre-datatable filter-left"></div>
					<diV>
		<table class="table table-advanced table-layout-auto" id = "table_request_changes">
			<thead>
				<tr>
					<th width="5%"></th>
					<th width="25%">Request Sub Type</th>
					<th width="15%">Request Action</th>
					<th width="50%">Request Details</th>
				</tr>
			</thead>
			<tbody>
			
			</tbody>
		</table>
			
		</div>
	</div>
		</div>
	</div>
</div>
</div>
	<div class="form-float-label" >
		<input type="hidden" name="id" value="<?php echo $id ?>"/>
		<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
		<input type="hidden" name="token" value="<?php echo $token ?>"/>
		<input type="hidden" name="action" id="action" value="<?php echo $action ?>"/>
		<input type="hidden" name="module"  id="module" value="<?php echo $module ?>"/>
		<input type="hidden" id="request_id" value="<?php echo $request_id ?>"/>
		<input type="hidden" id="total_days" value="<?php echo isset($leave_details) ? $leave_details['no_of_days'] + $leave_details['no_of_days_wop']:'0'?>"/>
		<?php if($action != ACTION_VIEW):?>
		<?php if(isset($leave_details['commutation_flag']) AND $leave_details['commutation_flag'] == "N"):?>
		<div class="row b-t b-light-gray">
		  <div class="col s6">
			<div class="input-field">
			  <label for="with_pay" class="active">Days With Pay <span class="required"> * </span></label>
			  <input type="text" id="with_pay" name="with_pay" class="validate" required="" value="<?php echo isset($leave_details['no_of_days']) ? $leave_details['no_of_days']:'0'?>" style="text-align: right" >
			</div>
		  </div>
		  <div class="col s6">
			<div class="input-field">
			  <label for="without_pay" class="active">Days Without Pay <span class="required"> * </span></label>
			  <input type="text" id="without_pay" name="without_pay" class="validate" required="" value="<?php echo isset($leave_details['no_of_days_wop']) ? $leave_details['no_of_days_wop']:'0'?>" style="text-align: right" >
			</div>
		  </div>
		</div>
		 <?php endif;?>
		<div class="row b-t b-light-gray">
		  <div class="col s12">
			<div class="input-field">
			  <label for="task_action" class="active">Action <span class="required"> * </span></label>
			  <select id="task_action" name="task_action" class="selectize" placeholder="Select Action">
				<option value="">Select Action</option>
				<?php foreach ($actions as $action): ?>
			  		<option value="<?php echo $action["process_action_id"]?>"><?php echo $action["name"]?></option>
			    <?php endforeach; ?>
			  </select>
			</div>
		  </div>
		</div>
		 <?php endif;?>
		<div class="row <?php echo $action ==ACTION_VIEW ? 'b-t b-light-gray' : '' ?>">
		  <div class="col s12">
			<div class="input-field">
			  <label for="task_remarks" class="<?php echo $action ==ACTION_VIEW ? 'active' : '' ?>">Note <span class="required"> * </span></label>
			 	<textarea name="task_remarks" class="materialize-textarea" id="task_remarks" <?php echo $action ==ACTION_VIEW ? 'disabled' : '' ?>><?php if($action == ACTION_VIEW) echo isset($task_info['remarks']) ? $task_info['remarks']:""?></textarea>				
			</div>
		  </div>
		</div>
	</div>
	<?php if($action != ACTION_VIEW):?>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
	    <button type="button" id="process_task" class="btn " value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	</div>
	<?php endif;?>
</form>
<script>
$(document).ready(function(){

	var total_days = $('#total_days').val();
	$("#with_pay").off("keyup");
	$("#with_pay").on("keyup",function(e){
		if(!parseFloat($('#with_pay').val()))
		{
			$('#with_pay').val(0);
		}
		var new_total = parseFloat($('#with_pay').val()) + parseFloat($('#without_pay').val());
	
		if(new_total > total_days)
		{
			var new_with = total_days - parseFloat($('#without_pay').val());
			$(this).val(new_with);
		}
		else
		{
			var new_without = total_days - parseFloat($('#with_pay').val());
			$('#without_pay').val(new_without);
		}
	});
	$("#without_pay").off("keyup");
	$("#without_pay").on("keyup",function(e){
	
		if(!parseFloat($('#without_pay').val()))
		{
			$('#without_pay').val(0);
		}
		var new_total = parseFloat($('#with_pay').val()) + parseFloat($('#without_pay').val());
	
		if(new_total > total_days)
		{
			var new_without = total_days - parseFloat($('#with_pay').val());
			$(this).val(new_without);
		}
		else
		{
			var new_with = total_days - parseFloat($('#without_pay').val());
			$('#with_pay').val(new_with);
		}
	});
	$('.input-field label').addClass('active');
	$('#task_form').parsley();

	$("#process_task").off("click");
	$("#process_task").on("click",function(e){
	
		$('#confirm_modal').confirmModal({
		    topOffset : 0,
		    onOkBut : function() {
		      	$('#task_form').trigger('submit');
		    },
		    onCancelBut : function() {},
		    onLoad : function() {
		      $('.confirmModal_content h4').html('Are you sure you want to proceed?'); 
		      $('.confirmModal_content p').html('This action will process this task and cannot be undone.');
		    },
		    onClose : function() {}
		});
	});
	

	jQuery(document).off('submit', '#task_form');
	jQuery(document).on('submit', '#task_form', function(e){
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $('#task_form').serialize();
		  	button_loader('process_task', 1);
		  	var option = {
					url  : $base_url + 'main/requests/process_task',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);
							modal_process_task.closeModal();
							var data_post = {
									'request_id'	: $('#request_id').val(),
									'request_module': $('#module').val(),
									'request_action': $('#action').val()
								};
							load_datatable('table_request_workflow', '<?php echo PROJECT_MAIN ?>/requests/get_request_tasks',false,0,0,true,data_post);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('process_task', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
});

function include_sub_request(action, id, token, salt, module, request_sub_id, this_type){
	
	if($(this_type).is(":checked"))
	{
		var type = "approve";
	}
	else
	{
		var type = "reject";
	}
	var data = {
		'process_action': type,
		'action'		: action,
		'id'			: id,
		'token'			: token,
		'salt'			: salt,
		'module'		: module
	};
	  var option = {
			url  :  $base_url + 'main/requests/process_subrequest',
			data : data,
			success : function(result){
				if(result.status)
				{
					notification_msg("<?php echo SUCCESS ?>", result.message);		
					$(this_type).attr('checked', true);								
				}
				else
				{
					notification_msg("<?php echo ERROR ?>", result.message);
					$('#check_'+request_sub_id).prop('checked', true);	
				}	
				
			},
			
			complete : function(jqXHR){
			}
	};

	General.ajax(option); 
}
</script>