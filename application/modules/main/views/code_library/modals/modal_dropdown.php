
	<form id = "form_dropdown" class = "form-horizontal">
		<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
		<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
		<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
		<input type="hidden" name="action" id="actiod_id" value="<?php echo !EMPTY($action_id) ? $action_id : NULL?>">
		
		<div class="form-float-label p-t-sm" id="div_row">
			<div class="row">
				<div class="col s12">
					<div class="input-field">
						<label class="<?php echo $action_id == ACTION_EDIT || $action_id == ACTION_VIEW ? 'active' :'' ?>" for="dropdown_name">Dropdown Name<span class="required">*</span></label>

						<input id="dropdown_name" <?php echo ACTION_VIEW == $action_id ? 'disabled' : NULL?> type="text" value="<?php echo isset($drop_down['dropdown_name']) ? $drop_down['dropdown_name'] : NULL?>" name="dropdown_name" class="form-control input-sm input-medium" data-parsley-required="true" data-parsley-trigger="keyup">
					</div>
				</div>
			</div>
			<div class="row m-n b-t b-light-gray">
				<div class="col s12">
					<div class="input-field">
					  	<label for="columns" class="active">Select<span class="required">*</span></label>
						<input id="columns" type="text" <?php echo ACTION_VIEW == $action_id ? 'disabled' : NULL?> name="columns" placeholder="Enter column fields" value="<?php echo isset($drop_down['columns']) ? $drop_down['columns'] : NULL?>" class="validate" required data-parsley-trigger="change">
				    </div>
			    </div>
			</div>	
			<div class="row m-n b-t b-light-gray">
				<div class="col s12">
					<div class="input-field">
						<label for="main_table" class="active">From<span class="required">*</span></label>
						<select class="selectize" name="table" <?php echo ACTION_VIEW == $action_id ? 'disabled' : NULL?> id="main_table" onchange="get_table_fields()" placeholder="Select Table" type="text" class="validate" required>
							<option>Select Table</option>
							<?php
								if(!EMPTY($tables)):
									foreach ($tables as $tbl)
									{
										if($tbl['table_name'] != $prev_table_name)
										{
											$selected = "";
											if(isset($drop_down['table_name']) AND $drop_down['table_name'] == $tbl['table_name'])
												$selected = "selected";
											echo "<option value='".$tbl['table_name']."' ".$selected.">".strtoupper($tbl['table_name'])."</option>";
											$prev_table_name = $tbl['table_name'];
										}
									}
								endif;
							?>
						</select>
					</div>
				</div>
			</div>
			<!-- WHERE -->
			<?php if($action_id == ACTION_ADD OR $dd_where == FALSE ): ?>
			<div class="list-group-item" id="where_div">
				<div class ="row">
					<label class="col s2 p-t-sm center">WHERE</label>
					<input type="hidden" name="where_condition[]" id="where_condition_1" value="WHERE">
					<div class="col s3">
						<select name="where_field[]" id="where_field_0"  placeholder="Select Fields" type="text" class="selectize validate where_field">
							<option>Select column</option>
						</select>
					</div>
					<div class="col s2">
						<select name="where_operator[]" id="where_operator_1"  placeholder="Operator" type="text" class="selectize validate">
							<option value='='>=</option>
							<option value='!='>!=</option>
							<option value='<='><=</option>
							<option value='>='>>=</option>
							<option value='LIKE'>LIKE</option>
						</select>
					</div>
					<div class="col s2">
						<input type="text" name="where_value[]" id="where_value_1"  placeholder="where value" value="" class="form-control input-sm" >
					</div>
					<div class="col s1">
						<a class="btn btn-success left" name="add_table" id="add_table" onclick="add_where()"><i class="flaticon-add175"></i>Where</a>
					</div>
				</div>
			</div>
			<?php ELSE: ?>
				<?php echo $display_view; ?>
			<?php ENDIF; ?>
			<input type="hidden" id="cond_counter" value="<?php echo $cond_counter; ?>">
		</div>
		<div class="md-footer default">
			<?php if($action_id != ACTION_VIEW && $action_id != ACTION_HISTORY) : ?>
				<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
				<button class="btn btn-success " id="save_dropdown" value="<?php echo ($action_id == ACTION_DOWNLOAD) ? BTN_DOWNLOAD : BTN_SAVE ?>"><?php echo ($action == ACTION_DOWNLOAD) ? BTN_DOWNLOAD : BTN_SAVE ?></button>
			<?php endif; ?>
		</div>
	</form>

<script>
var sel_option = '';

// get_table_fields();

function get_table_fields()
{
	var selected_table  = document.getElementById('main_table').value;

	var data_dictionary = <?php echo json_encode($tables) ?>;
	var result          = '';
	if(selected_table != 'Select Table')
	{	
		$('#where_div').find('select')[0].selectize.destroy();

		// console.log($('.where_field'));
		for (var i=0; i < data_dictionary.length; i++)
		{	
			result += '<option>Select column</option>';
			if(data_dictionary[i]['table_name'] == selected_table)
			{
				result += '<option value="' +  data_dictionary[i]['column_name'] + '">' + data_dictionary[i]['column_name'] + '</option>';
			}
		}

		$('.where_field').html(result);
		$('.where_field').selectize();
	}
}

function add_where()
{
	var cond_counter = parseInt($("#cond_counter").val())+1;
	
	var selected_table  = document.getElementById('main_table').value;

	var data_dictionary = <?php echo json_encode($tables) ?>;
	var result          = '';

	for (var i=0; i < data_dictionary.length; i++)
	{	result += '<option>Select column</option>';
		if(data_dictionary[i]['table_name'] == selected_table)
		{
			result += '<option value="' +  data_dictionary[i]['column_name'] + '"  >' + data_dictionary[i]['column_name'] + '</option>';
		}
	}

	$("#where_div").append(
		'<div class = "row " id="where_condition_div_'+cond_counter+'">'+
			'<div class="col s2">'+
				'<select name="where_condition[]" placeholder="Condition" type="text" class="selectize validate" required="true">'+
					'<option value="AND">AND</option>'+
					'<option value="OR">OR</option>'+
				'</select>'+
			'</div>'+
			'<div class="col s3">'+
				'<select name="where_field[]" placeholder="Select Fields" type="text" class="selectize validate where_field" required="true">'+
					result+
				'</select>	'+
			'</div>'+
			'<div class="col s2">'+
				'<select name="where_operator[]" placeholder="Operator" type="text" class="selectize validate" required="true">'+
					'<option value="=">=</option>'+
					'<option value="!=">!=</option>'+
					'<option value="<="><=</option>'+
					'<option value=">=">>=</option>'+
					'<option value="LIKE">LIKE</option>'+
				'</select>'+
			'</div>'+
			'<div class="col s2">'+
				'<input type="text" name="where_value[]" placeholder="where value" class="browser-default left validate" required="true">'+
			'</div>'+
			'<div class="col s1">'+
				'<a onclick="delete_condition(this)" id="delete_condition_'+cond_counter+'" title="Delete Condition" class="btn btn-xs default"> <i class="Small flaticon-minus102"></i></a>'+
			'</div>'+
		'</div>'
	);
	$('#where_div select').selectize();
	$('#cond_counter').val(cond_counter);
}
function delete_condition(elem)
{
	var delete_id 	= elem.id;
	var arr_data	= delete_id.split("_");
	var del_id		= arr_data[2];
	var div_id		= 'where_condition_div_'+del_id;
	
	$('#' + div_id).remove();
}

$(function (){
	$('#form_dropdown').parsley();
	$('#form_dropdown').submit(function(e) {
		e.preventDefault();

		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
			button_loader('save_dropdown', 1);
			var option = {
				url  : $base_url + 'main/code_library_system/dropdown/process',
				data : data,
				success : function(result){
					if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_dropdown.closeModal();
							load_datatable('dropdown_table', '<?php echo PROJECT_MAIN ?>/code_library_system/dropdown/get_dropdown_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
				},

				complete : function(jqXHR){
				button_loader('save_dropdown', 0);
				}
			};

			General.ajax(option);    
		}
	});

	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
		<?php } ?>
})
</script>
