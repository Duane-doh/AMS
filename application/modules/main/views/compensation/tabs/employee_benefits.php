<form id="employee_benefits_form">
	<input type="hidden" name="employee_id" id="employee_id" value="<?php echo !EMPTY($employee_id) ? $employee_id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<input type="hidden" name="module" id="module" value="<?php echo !EMPTY($module) ? $module : NULL?>">
	<?php if($module == MODULE_HR_COMPENSATION && $action == ACTION_EDIT && $has_permission):?>
	<div class="col s12 p-r-sm">
		<div class="col s7">
			&nbsp;
		</div>
		<div class="col s5" style="text-align: right;">
	 		<button class="btn btn-success" type="button" id="add_benefits"><i class="flaticon-add175"></i> Add Benefit</button>
			&nbsp;&nbsp;
			<button class="btn btn-success" id="save_employee_benefits" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
		</div>
	</div>
	<?php endif;?>
	<div class="col s12 p-t-sm">
		<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employe_benefit_list">
		  <thead style="background:#EEE"> 
			<tr>
			  <th width="40%">Benefit</th>
			  <th width="20%">Start Date</th>
			  <th width="20%">&nbsp;&nbsp;&nbsp;End Date<br>(&nbsp;<i>Last Day</i>&nbsp;)</th>
			  <?php if($module == MODULE_HR_COMPENSATION && $action == ACTION_EDIT):?>
			  <th width="10%">Actions</th>
	 		  <?php endif;?>
			</tr>
		  </thead>
			  <tbody id="div_rows">
			  <?php if(!EMPTY($employee_benefits)) :
			  		foreach($employee_benefits AS $key => $benefit)
					{
						if($module == MODULE_HR_COMPENSATION && $action == ACTION_EDIT && $has_permission) :
							echo '<tr id="table_row_' . $key . '">'
								 . '<td class="">' . $benefit['compensation_name'] . '<input type="hidden" class="validate" name="compensation['.$key.'][compensation_id]" value="' . $benefit['compensation_id'] . '"></td>'
								 . '<td><input type="text" class="validate datepicker datepicker_start center" name="compensation['.$key.'][start_date]" required="" aria-required="true" value="' . format_date($benefit['start_date']) . '"> </td>'
								 // . '<td><input type="text" class="validate datepicker datepicker_min_today center" name="compensation['.$key.'][end_date]" value="' . (!EMPTY($benefit['end_date']) ? format_date($benefit['end_date']) : '') . '"> </td>'
								 // ====================== jendaigo : start : remove class datepicker_min_today ============= //
								 . '<td><input type="text" class="validate datepicker center" name="compensation['.$key.'][end_date]" value="' . (!EMPTY($benefit['end_date']) ? format_date($benefit['end_date']) : '') . '"> </td>'
								 // ====================== jendaigo : end   : remove class datepicker_min_today ============= //
								 . '<td> <div class="table-actions p-t-sm"><a id="remove_table_' . $key .'" href="javascript:;" onclick="remove_table(' . $key . ',' . $benefit['employee_compensation_id'] . ')" class="delete tooltipped" data-tooltip="Delete" data-position="bottom" data-delay="50"></a></div></td></tr>';
						 else :
							echo '<tr id="table_row_' . $key . '">'
								 . '<td class="p-t-md">' . $benefit['compensation_name'] . '<input type="hidden" class="validate" name="compensation['.$key.'][compensation_id]" value="' . $benefit['compensation_id'] . '"></td>'
								 . '<td class="p-t-md"><center>' . (!EMPTY($benefit['start_date']) ? format_date($benefit['start_date']) : '') .'</center></td>'
								 . '<td class="p-t-md"><center>' . (!EMPTY($benefit['end_date']) ? format_date($benefit['end_date']) : '') .'</center></td>'
								 . '<td>&nbsp;</td></tr>';
						 endif;
					}
					else :
						if($module != MODULE_HR_COMPENSATION || $action != ACTION_EDIT) {
							echo '<tr><td class="p-t-md">No matching records found</td></tr>';
						}

			   	endif; ?>
			  </tbody>
		</table>
	</div>
</form>

<table>
	<tr id="table_row" style="display:none!important">
		<td><select class="validate" type="text" required="" aria-required="true" name="compensation[]" id="compensation_id" placeholder="Select compensation...">
		</select></td>
		<td><input type="text" class="validate datepicker datepicker_start center" required="" aria-required="true" name="start_date" id="start_date"></td>
		<td><input type="text" class="validate datepicker datepicker_end center" name="end_date" id="end_date"></td>
		<td><div class="table-actions p-t-sm"><a id="remove_table" href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div></td>
	</tr>
</table>

<script type="text/javascript">
$(document).ready(function(){

	var row_index = <?php echo count($employee_benefits) ?>;


	$('#add_benefits').on('click', function() {
		var clonerow = $("#table_row");
		var compensation_list = <?php echo json_encode($compensation_type_list) ?>;
		clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").prependTo("#div_rows");
		var newrow = $("#table_row_" + row_index);
		// RENAME SELECT ELEMENT
		newrow.find('select').attr({
			id : 'compensation_name_'+row_index,
			name : 'compensation['+row_index+'][compensation_id]'
		});
		// RENAME INPUT STARTDATE ELEMENT
		newrow.find('input.datepicker_start').attr({
			name : 'compensation['+row_index+'][start_date]'
		});
		// RENAME INPUT ENDDATE ELEMENT
		newrow.find('input.datepicker_end').attr({
			name : 'compensation['+row_index+'][end_date]'
		});
		var option = '<option></option>';
		for(var i=0; i<compensation_list.length;i++)
		{
			option += '<option value="' + compensation_list[i]['compensation_id'] + '">' + compensation_list[i]['compensation_name'] + '</option>';
		}
		// $('#compensation_name_'+row_index).selectize.destroy();
		$('#compensation_name_'+row_index).html(option).selectize();
		$('#table_row_' + row_index + ' .selectize-input input').attr('style', 'height:15px !important');

		newrow.find('a').attr({
			id: "remove_table_" + row_index ,
			onclick: "remove_table("+row_index+",0)"
		});
		$('.datepicker').datetimepicker('destroy');
		$('.datepicker_start').datetimepicker({
			timepicker:false,
			format:'Y/m/d'
		});
		$('.datepicker_end').datetimepicker({
			timepicker:false,
			format:'Y/m/d',
			minDate: '+1'
		});
		row_index++; 
	});

	var is_hr_module = <?php echo $module == MODULE_HR_COMPENSATION ? 1 : 0 ?>;

	if(row_index == 0 && is_hr_module) {
		$('#add_benefits').trigger('click');
	}

});


$(function (){
	$('#employee_benefits_form').parsley();
	$('#employee_benefits_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_employee_benefits', 1);
		  	var option = {
					url  : $base_url + 'main/compensation/process_employee_benefit',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);

							$('#tab_content.compensation').load('<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_BENEFITS."/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>')

							
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_employee_benefits', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
  	
  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})


function remove_table(row_index,id)
{
	if(id > 0)
	{
		$('#confirm_modal').confirmModal({
			topOffset : 0,
			onOkBut : function() {

				var data ='employee_compensation_id='+id;
			  	var option = {
					url  : $base_url + 'main/compensation/delete_employee_benefit',
					data : data,
					success : function(result){
						if(result.flag) {
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							$("#table_row_" + row_index).remove();
 							var rowCount = $('#table_employe_benefit_list tr').length;
 							if(rowCount == 1) {
 								$('#add_benefits').trigger('click');
 							}
						}
						else {
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
					}
				};
				General.ajax(option); 

			},
			onCancelBut : function() {},
			onLoad : function() {
				$('.confirmModal_content h4').html('Are you sure you want to delete this Benefit?');	
				$('.confirmModal_content p').html('This action will permanently delete this record from the database and cannot be undone.');
			},
			onClose : function() {}
		});
		
	}
	else
	{
		$("#table_row_" + row_index).remove();
	}
}

</script>	