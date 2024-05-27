<?php 
$disabled = '';
if($action == ACTION_VIEW) {
	$disabled = 'disabled';
}
?>
<form id="download_template_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	<?php if($action == ACTION_EDIT || $action == ACTION_ADD || $action == ACTION_VIEW) : ?>
	<div class="form-float-label">
		<div class="row">
			<div class="col s4">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT || $action == ACTION_VIEW ? 'active' :'' ?>" for="hdr_download_name">Data Download Name <?php echo ($action != ACTION_VIEW ? '<span class="required">*</span>' : '')?></label>
					<input type="text" class="validate" required="" aria-required="true" name="hdr_download_name" id="hdr_download_name" value="<?php echo $download_template['name']; ?>" <?php echo $disabled; ?>/>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<label for="hdr_status" class="active">Status <?php echo ($action != ACTION_VIEW ? '<span class="required">*</span>' : '')?></label>
					<select id="hdr_status" required name="hdr_status" class="selectize" placeholder="Select status" <?php echo $disabled; ?>>
						<option>Select Status</option>
						<?php 
						$status   = array();
						$status[] = 'Draft';
						$status[] = 'Published';
						for ($i=0; $i < count($status); $i++) 
						{ 
							$seleted = '';
							if($download_template['status'] == $i)
							{
								$seleted = 'selected';
							}

							echo '<option value="' . $i . '" ' . $seleted . '>' . $status[$i] . '</option>';
						} 
						?>
					</select>
				</div>
			</div>
			<div class="col s4">
				<div class="input-field">
					<label for="hdr_group" class="active">Table Group</label>
					<select id="hdr_group" name="hdr_group" class="selectize" placeholder="Select Table Group" onchange="get_table_names()" <?php echo $disabled; ?>>
					<option></option>
						<?php 
						foreach($table_group AS $g)
						{
							$selected = ($download_template['group_hdr_id'] == $g['group_hdr_id'] ? 'selected' : '');
							echo '<option value="' . $g['group_hdr_id'] . '" ' . $selected . '>' . $g['group_name'] . '</option>';
						}

						?>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT || $action == ACTION_VIEW ? 'active' :'' ?>" for="check_list_description">Description </label>
					<input type="text" class="validate" name="hdr_description" id="check_list_description" value="<?php echo $download_template['description']; ?>" <?php echo $disabled; ?>/>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label class="<?php echo $action == ACTION_EDIT || $action == ACTION_VIEW ? 'active' :'' ?>" for="check_list_description">Notes </label>
					<input type="text" class="validate" name="hdr_notes" id="check_list_description" value="<?php echo $download_template['notes']; ?>" <?php echo $disabled; ?>/>
				</div>
			</div>
		</div>
	</div>
	<div class="p-sm">
		<?php if($action != ACTION_VIEW) : ?>
		<div class="p-r-sm">
			<a class="btn green right" id="add_select_table"><i class="flaticon-add175"></i>ADD</a>
		</div>
		<?php endif; ?>
		<div class="col s12 p-t-sm">
			<table class="striped table-default" id="template_table">
				<thead class="teal white-text">
					<tr>
						<td width = "30%" class="font-semibold">Table <?php echo ($action != ACTION_VIEW ? '<span class="required">*</span>' : '')?></td>
						<td width = "20%" class="font-semibold">Column <?php echo ($action != ACTION_VIEW ? '<span class="required">*</span>' : '')?></td>
						<td width = "20%" class="font-semibold">Dropdown</td>
						<td width = "10%" class="font-semibold">Start Value</td>
						<td width = "10%" class="font-semibold">End Value</td>
						<td width = "10%" class="font-semibold">Action</td>
					</tr>
				</thead>
				<tbody id="div_rows">
				<?php
					if(!empty($download_template_dtl)) :
					foreach($download_template_dtl AS $i => $r) :
				?>
					<tr id="table_row_<?php echo $i; ?>">
						<td>
							<select class="browser-default left validate table_name" name="table[]" onchange="get_table_fields(<?php echo $i; ?>)" id="table_<?php echo $i; ?>" required="true" <?php echo $disabled; ?>>
							<option>Select Table</option>
							<?php
								if(!EMPTY($download_template['group_hdr_id'])) {

									foreach($table_group_columns as $group) :
										echo '<option value="' . $group['table_name'] . '"' . ($group['table_name'] == $r['table_name'] ? 'selected' : '') . ' >' . $group['table_name'] . '</option>';
									endforeach;

								} else {
									foreach($data_dictionary AS $table) :
										if($table['table_name'] != $prev_table_name) {
											echo '<option value="' . $table['table_name'] . '"' . ($table['table_name'] == $r['table_name'] ? 'selected' : '') . ' >' . $table['table_name'] . '</option>';

											$prev_table_name = $table['table_name'];
										}
									endforeach;
								}
							?>
							</select>
						</td>
						<td>
							<select class="browser-default left field_name" id="field_<?php echo $i; ?>" name="field[]" required="true" <?php echo $disabled; ?>>
							<option>Select Column</option>
							<?php
								foreach($data_dictionary AS $table) :
									if($table['table_name'] == $r['table_name']) {
										echo '<option value="' . $table['column_name'] . '"' . ($table['column_name'] == $r['field_name'] ? 'selected' : '') . ' >' . $table['column_name'] . '</option>';
									}
								endforeach;
							?>
							</select>
						</td>
						<td>
							<select class="browser-default left" id="dropdown_<?php echo $i; ?>" name="dropdown[]" <?php echo $disabled; ?>>
								<option>Select Dropdown</option>
								<?php
								foreach($dropdown AS $dd) {
									echo '<option value="' . $dd['dropdown_id'] . '"' . ($dd['dropdown_id'] == $r['dropdown'] ? 'selected' : '') . ' >' . $dd['dropdown_name'] . '</option>';
								}
								?>
							</select>
						</td>
						<td>
							<input type="text" placeholder="Start Value" class="validate browser-default" name="column_start_value[]" id="column_start_value" value="" <?php echo $disabled; ?>/>
						</td>
						<td>
							<input type="text" placeholder="End Value" class="validate browser-default" name="column_end_value[]" id="column_end_value" value="" <?php echo $disabled; ?>/>
						</td>
						<td>
							<?php if($action != ACTION_VIEW) : ?>
							<div class="table-actions p-t-sm"><a href='javascript:;' id="remove_table_<?php echo $i ?>" onclick='remove_table(<?php echo $i ?>)' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div>
							<?php endif; ?>
						</td>
					</tr>	
				<?php
					$i++;
						endforeach;
					else:
				?>
					<tr id="table_row_0">
						<td>
							<select class="browser-default left validate table_name" name="table[]" onchange="get_table_fields(0)" id="table_0" required="true">
							<option value="" disabled="" selected="">Select Table</option>
							<?php
								foreach($data_dictionary AS $table) :
									if($table['table_name'] != $prev_table_name)
									echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
									$prev_table_name = $table['table_name'];
								endforeach;
							?>
							</select>
						</td>
						<td>
							<select class="browser-default left field_name" id="field_0" name="field[]" required="true">
							<option value=""> Select Column</option>
							
							</select>
						</td>
						<td>
							<select class="browser-default left" id="dropdown_0" name="dropdown[]">
								<option>Select Dropdown</option>
								<?php
									foreach($dropdown AS $dd)
									{
										echo '<option value="' . $dd['dropdown_id'] . '">' . $dd['dropdown_name'] . '</option>';
									}
								?>
							</select>
						</td>
						<td>
							<input type="text" placeholder="Start Value" class="validate browser-default" name="column_start_value[]" id="column_start_value" value=""/>
						</td>
						<td>
							<input type="text" placeholder="End Value" class="validate browser-default" name="column_end_value[]" id="column_end_value" value=""/>
						</td>
						<td>
							<!-- <div class="table-actions p-t-sm"><a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div> -->
						</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php elseif($action == ACTION_DOWNLOAD) :?>	

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label>Data Download Name: <b><?php echo $download_template['name']?></b> </label>
				</div>
			</div>
			<div class="col s6">
				<div class="input-field">
					<label for="description">Description  <span class="required">*</span></label>
					<input type="text" class="validate" required="" name="description" value=""/>
				</div>
			</div>
		</div>
	</div>
	<div class="p-sm">
		<div class="col s12 p-t-sm">
			<table class="striped table-default" id="template_table">
				<thead class="teal white-text">
					<tr>
						<td width = "20%" class="font-semibold">Table</td>
						<td width = "20%" class="font-semibold">Column</td>
						<td width = "20%" class="font-semibold">Dropdown</td>
						<td width = "15%" class="font-semibold">Start Value</td>
						<td width = "15%" class="font-semibold">End Value</td>
						<td width = "10%" class="font-semibold">Transpose</td>
					</tr>
				</thead>
				<tbody id="div_rows">
				<?php echo $view; ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php else : ?>
	<div class="form-float-label">
		<div class="row">
			<div class="col s12">
				<div class="input-field">
					<label class="active">Data Download Name:</label>
					<input type="text" value="<?php echo $download_template['name']?>" disabled>
				</div>
			</div>
		</div>
	</div>
	<div class="p-sm">
		<div class="col s12 p-t-sm">
			<table class="striped table-default" id="template_table">
				<thead class="teal white-text">
					<tr>
						<td width = "30%" class="font-semibold">Remarks</td>
						<td width = "20%" class="font-semibold">Downloaded By</td>
						<td width = "20%" class="font-semibold">Date Downloaded</td>
						<td width = "10%" class="font-semibold">Action</td>
					</tr>
				</thead>
				<tbody id="div_rows">
				<?php echo $view; ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php endif; ?>

	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<?php if($action != ACTION_VIEW && $action != ACTION_HISTORY) : ?>
		<button class="btn btn-success " id="save_download_template" value="<?php echo ($action == ACTION_DOWNLOAD) ? BTN_DOWNLOAD : BTN_SAVE ?>"><?php echo ($action == ACTION_DOWNLOAD) ? BTN_DOWNLOAD : BTN_SAVE ?></button>
		<?php endif; ?>
	</div>
</form>

<table>
	<tr id="table_row" style="display:none!important">
  		<td>
			<select class="browser-default left table_name" onchange="get_table_fields(0)" name="table[]" id="table" required="true" placeholder="Select Table">
			</select>
		</td>
		<td>
			<select class="browser-default left field_name" name="field" id="field" required="true">
			<option value=""> Select Column</option>
			
			</select>
		</td>
		<td>
			<select class="browser-default left" id="dropdown_">
				<option>Select Dropdown</option>
				<?php
					foreach($dropdown AS $dd)
					{
						echo '<option value="' . $dd['dropdown_id'] . '">' . $dd['dropdown_name'] . '</option>';
					}
				?>
			</select>
		</td>
		<td>
			<input type="text" placeholder="Start Value" class="validate browser-default" name="column_start_value[]" id="column_start_value" value=""/>
		</td>
		<td>
			<input type="text" placeholder="End Value" class="validate browser-default" name="column_end_value[]" id="column_end_value" value=""/>
		</td>
		<td>
			<div class="table-actions p-t-sm"><a id="remove_table" href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a></div>
		</td>
	</tr>
</table>

<script>

function get_table_names(row_index)
{
	if(row_index == null)
	var div_rows = $("#div_rows");
	else
	var div_rows = $('#table_row_' + row_index);

	// var selected_index = $('#hdr_group')[0].selectedIndex;
	var selected_group = $('#hdr_group')[0].value;
	if(selected_group > 0)
	{
		var selected_group_list = <?php echo json_encode($table_group_columns) ?>;
		div_rows.find('select.table_name').each(function(){
			var id     = $(this).attr("id");
			var result = '';
			for (var i=0; i < selected_group_list.length; i++)
			{
				if(selected_group_list[i]['group_hdr_id'] == selected_group)
				{
					result += '<option value="' +  selected_group_list[i]['table_name'] + '"  >' + selected_group_list[i]['table_name'] + '</option>';

				}
			}
			

			$(this).html(result);
			get_table_fields(id.substr(id.indexOf('_')+1));

		});
	}
	else
	{
		var data_dictionary_list = <?php echo json_encode($data_dictionary) ?>;
		div_rows.find('select.table_name').each(function(){
			var id     = $(this).attr("id");
			var result = '';
			var prev_table_name = '';
			for (var i=0; i < data_dictionary_list.length; i++)
			{
				if(data_dictionary_list[i]['table_name'] != prev_table_name)
				{
					result += '<option value="' +  data_dictionary_list[i]['table_name'] + '"  >' + data_dictionary_list[i]['table_name'] + '</option>';

				}
				prev_table_name = data_dictionary_list[i]['table_name'];

			}

			$(this).html(result);
			get_table_fields(id.substr(id.indexOf('_')+1));

		});
	}
}

function get_table_fields(id)
{
	var selected_table = document.getElementById('table_'+id).value;

	var data_dictionary = <?php echo json_encode($data_dictionary) ?>;
	var result = '';

	for (var i=0; i < data_dictionary.length; i++)
	{
		if(data_dictionary[i]['table_name'] == selected_table)
		{
			result += '<option value="' +  data_dictionary[i]['column_name'] + '"  >' + data_dictionary[i]['column_name'] + '</option>';

		}
	}

	$('#field_' + id).html(result);
}



function remove_table(row_index)
{
	$("#table_row_" + row_index).remove();
}

// FOR ADDING ROW
// START
var row_index = $('#template_table tr').length - 1;

$('#add_select_table').on('click', function()
{
	console.log('row added');
	var clonerow = $("#table_row");
	
	
	// clone the row
	clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").appendTo("#div_rows");
	// handleSelect2();
	
	var newrow = $("#table_row_" + row_index);
	
	// assign id and name to selectize of newly created row 
	newrow.find('select.table_name').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + "["+row_index+"]";
		$(this).attr({
			name: myname,
			id: myvar,
			onchange: 'get_table_fields(' + row_index + ')'
		}).val('');
	});

	newrow.find('select.field_name').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + "["+row_index+"]";
		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
	});

	newrow.find('input').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + '[' + row_index + ']';

		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
		
	});


	newrow.find('a').attr({
		id: "remove_table_" + row_index ,
		onclick: "remove_table("+row_index+")"
	});
	get_table_names(row_index);
	row_index++; 
});
// END

$(function ()
{
	$('#download_template_form').parsley();
	$('#download_template_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
			data += '&module=data_download';
		  	button_loader('save_download_template', 1);
		  	var option = {
					url  : $base_url + 'main/adhoc_reports/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							modal_data_download.closeModal();
							if(result.reference_no === undefined)
							{
								notification_msg("<?php echo SUCCESS ?>", result.msg);	
							}
							else
							{
								window.location = $base_url + 'main/adhoc_reports/download_data'+'/'+result.reference_no+'/'+result.download_history_id+'/'+result.remarks;
							}
							load_datatable('data_download_table', '<?php echo PROJECT_MAIN ?>/adhoc_reports/get_data_download_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_download_template', 0);
					}
			};

			General.ajax(option);    
	    }
  	});
})

</script>