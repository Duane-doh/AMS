<?php

$joins = array();
$joins[] = array( 'code' => 'JOIN', 'name' => 'JOIN' );
$joins[] = array( 'code' => 'LEFT JOIN', 'name' => 'LEFT JOIN' );
$joins[] = array( 'code' => 'RIGHT JOIN', 'name' => 'RIGHT JOIN' );

if(!EMPTY($group_hdr)) {
	$table_name = '';
	$temp_arr = explode('_', $group_hdr['group_name']);
	for ($i=1; $i < count($temp_arr); $i++) { 
		$table_name .= $temp_arr[$i];
		if(($i+1) < count($temp_arr)) {
			$table_name .= '_';
		}
	}
}

$disabled = '';
if($action == ACTION_VIEW) {
	$disabled = 'disabled';
}
?>
<form id="table_group_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">
	
	<div class="scroll-pane" style="max-height:600px !important">
		<div class="form-float-label">
			<div class="row">
				<div class="col s1" style="background-color:#DEDBDB">
					<div class="input-field">
						<center><label> <b>PTIS_</b> </label></center>
					</div>
				</div>
				<div class="col s11">
					<div class="input-field">
						<label for="group_name" <?php echo $action == ACTION_EDIT || $action == ACTION_VIEW ? 'class="active"' : '' ?>>Group Name <?php echo ($action != ACTION_VIEW ? '<span class="required">*</span>' : '')?></label>
						<input type="text" class="validate active" required="" aria-required="true" name="group_name" id="group_name" value="<?php echo $table_name ?>" <?php echo $disabled; ?> />
					</div>
				</div>
			</div>
		</div>
		<div class="p-md" id="appending_div">
			<div class="row p-b-sm">
				<div class="col s6 p-t-sm">
					<p><b>SELECT *</b></p>
					<p><b>FROM</b></p>
					<select class="browser-default left" required="true" name="main_table" <?php echo $disabled; ?>>
						<option>Select Table</option>
						<?php
							foreach($data_dictionary AS $table) :
								if($table['table_name'] != $prev_table_name)
								{
									$selected = '';
									if(strtolower($group_hdr['table_name']) == strtolower($table['table_name']))
										$selected = 'selected';


									echo '<option value="' . $table['table_name'] . '"' . $selected . '>' . $table['table_name'] . '</option>';
								}
								$prev_table_name = $table['table_name'];
							endforeach;

						?>
					</select>
				</div>
				<div class="col s6 p-t-sm right">
					<p><b>&nbsp;</b></p>
					<p><b>&nbsp;</b></p>
					<?php if($action != ACTION_VIEW) : ?>
					<a class="btn btn-success left" name="add_table" id="add_table"><i class="flaticon-add175"></i>table</a>
					<?php endif; ?>
				</div>
			</div>
			<?php if($action == ACTION_ADD) :?>
			<div class="list-group-item" id="table_row_0">
				<div class="row">
					<div class="col s2">
						<select class="browser-default left" required="true" name="join[]" id="join_0">
							<option>Select Join</option>
							<?php
								foreach($joins AS $j)
								{
									echo '<option value="' . $j['code'] . '">' . $j['name'] . '</option>';
								}

							?>
							
						</select>
					</div>
					<div class="col s4">
						<select class="browser-default left field_name" required="true" name="join_table[]">
							<option>Select Table</option>
							<?php
								foreach($data_dictionary AS $table) :
									if($table['table_name'] != $prev_table_name) 
									{
										
										echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
									}
									$prev_table_name = $table['table_name'];
								endforeach;

							?>
						</select>
					</div>
					<div class="col s2">
						<p>
							<input type="checkbox" class="filled-in" name="on_condition[]" id="on_condition_0">
							<label for="on_condition_0">ON</label>
						</p>
					</div>

					<div class="col s4">
						<a class="btn right m-n delete" id="remove_table_0" onclick="remove_table(0)"><i class="Small flaticon-minus102"></i> TABLE</a>
					</div>
				</div>
				<div class="row p-t-sm">
					<div class="col s2">
						<select class="browser-default left table_name_a" onchange="get_table_fields('_a_',0)" required="true" name="alias_a[][]" id="alias_a_0" disabled>
							<option value="">Select table</option>
							<?php
							foreach($data_dictionary AS $table) :
								if($table['table_name'] != $prev_table_name)
								echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
								$prev_table_name = $table['table_name'];
								$main_prev_table_name = $table['table_name'];
							endforeach;
							?>
						</select>
					</div>
					<div class="col s3">
						<select class="browser-default left field_name" required="true" disabled name="field_a[][]" id="field_a_0" disabled>
							<option value="">No fields found.</option>	
						</select>
					</div>
					<div class="left">
						<p>=</p>
					</div>
					<div class="col s2">
						<select class="browser-default left table_name_b" onchange="get_table_fields('_b_',0)" required="true" name="alias_b[][]" id="alias_b_0" disabled>
							<option value="">Select table</option>
							<?php
							foreach($data_dictionary AS $table) :
								if($table['table_name'] != $prev_table_name)
								echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
								$prev_table_name = $table['table_name'];
								$main_prev_table_name = $table['table_name'];
							endforeach;
							?>
						</select>
					</div>
					<div class="col s3">
						<select class="browser-default left field_name" required="true" disabled="" name="field_b[][]" id="field_b_0" disabled>
							<option value="">No fields found.</option>
						</select>
					</div>
				</div>
				<div class="row p-t-sm">
					<div class="col s3">
						<a class="btn btn-success left m-n none" name="condition[]" id="condition_0"><i class="Small flaticon-add175"></i> CONDITION</a>
					</div>
				</div>
			</div>
		<?php
			endif; 
		?>
		</div>
	</div>
	<div class="md-footer default">
		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		<?php if($action != ACTION_VIEW) : ?>
		<button class="btn btn-success " id="save_table_group" value="<?php echo ($action == ACTION_DOWNLOAD) ? BTN_DOWNLOAD : BTN_SAVE ?>"><?php echo ($action == ACTION_DOWNLOAD) ? BTN_DOWNLOAD : BTN_SAVE ?></button>
		<?php endif; ?>
	</div>
</form>






<script>

var action = <?php echo $action ?>;

if(action == 2)
{
	// INIT functions
	// START
	get_table_fields('_a_',0);
	get_table_fields('_b_',0);
	joining_select(0);
	on_condition(0);
	add_condition(0,1);
	// var row_index = 1;

	// END
}
else
{
	populate_div();
	var row_index = <?php echo count($group_hdr['details']) ?>;
}



$(function (){
	
	$('#table_group_form').parsley();
	$('#table_group_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {

			// $('#group_name').val('ptis_' + $('#group_name').val());

			var data = $(this).serialize();
			data += '&module=table_group';
		  	button_loader('save_table_group', 1);
		  	var option = {
					url  : $base_url + 'main/adhoc_reports/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);	
							load_datatable('table_group_table', '<?php echo PROJECT_MAIN ?>/adhoc_reports/get_table_group_list',false,0,0,true);
							modal_table_group.closeModal();
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_table_group', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

})



$('#add_table').on('click', function()
{
	var clonerow = $("#add_row");

	// clone the row
	clonerow.clone().attr("id", "table_row_" + row_index).removeAttr("style").appendTo("#appending_div");
	
	var newrow = $("#table_row_" + row_index);
	
	// assign id and name to selectize of newly created row 

	newrow.find('select.join').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + "["+row_index+"]";
		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
	});

	newrow.find('select.table_name_a').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + "["+row_index+"][]";
		$(this).attr({
			name: myname,
			id: myvar,
			onchange: 'get_table_fields(\'_a_\',' + row_index + ')'
		}).val('');
	});

	newrow.find('select.table_name_b').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + "["+row_index+"][]";
		$(this).attr({
			name: myname,
			id: myvar,
			onchange: 'get_table_fields(\'_b_\',' + row_index + ')'
		}).val('');
	});

	newrow.find('select.field_name').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + "["+row_index+"][]";
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

	newrow.find('label').each(function(){
		var myvar = $(this).attr("for") + row_index;

		$(this).attr({
			for: myvar
		}).val('');
		
	});

	newrow.find('button').each(function(){
		var myvar = $(this).attr("id") + '_' + row_index;
		var myname = $(this).attr("id") + '[' + row_index + ']';

		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
		
	});


	newrow.find('a.delete').attr({
		id: "remove_table_" + row_index ,
		onclick: "remove_table("+row_index+")"
	});

	newrow.find('a.add_condition').attr({
		id: 'condition_' + row_index

	});
	
	// INIT functions
	// START
	joining_select(row_index);
	on_condition(row_index);
	add_condition(row_index,1);
	// END
	row_index++; 
});


function populate_div()
{
	var is_view         = <?php echo $action == ACTION_VIEW ? 1 : 0 ?>;
	var disabled        = (is_view) ? 'disabled' : '';
	var joins           = <?php echo json_encode($joins) ?>;
	var group_hdr       = <?php echo json_encode($group_hdr) ?>;
	var data_dictionary = <?php echo json_encode($data_dictionary) ?>;
	var group_hdr_dtl   = group_hdr['details'];

	for(var i=0; i < group_hdr_dtl.length; i++)
	{
		html = '<div class="list-group-item" id="table_row_' + i + '">'
				+ '<div class="row">'
				+ 	'<div class="col s2">'
	 			+		'<select class="browser-default left" required="true" name="join[]" id="join_' + i + '" ' + disabled + '>';
	 			
	 			for(var j=0; j < joins.length; j++)
	 			{
	 				var selected = '';
	 				if(joins[j]['code'] == group_hdr_dtl[i]['join_connection'])
	 					selected = 'selected';

	 	html += 			'<option value="' + joins[j]['code'] + '" ' + selected + '>' + joins[j]['name'] + '</option>';
	 			}
	 	html += 		'</select>'
	 			+	'</div>'
	 			+	'<div class="col s4">'
	 			+		'<select class="browser-default left field_name" required="true" name="join_table[]" ' + disabled + '>';
	 			var prev_table_name = '';
	 			for(var j=0; j<data_dictionary.length; j++)
	 			{
	 				if(prev_table_name != data_dictionary[j]['table_name'])
	 				{
	 				var selected = '';
	 				if(data_dictionary[j]['table_name'] == group_hdr_dtl[i]['table_name'])
	 					selected = 'selected';
	 	html +=				'<option value="' + data_dictionary[j]['table_name'] + '" ' + selected + '>' 
	 							+ data_dictionary[j]['table_name'] + '</option>';
	 				prev_table_name = data_dictionary[j]['table_name'];
	 				}
	 			}
	 	html +=			'</select>'
	 			+	'</div>'
	 			+	'<div class="col s2">'
	 			+		'<p>';
	 				var checked = '';
	 				if(group_hdr_dtl[i]['with_condition'])
	 					checked = 'checked';
	 	html +=				'<input ' + checked + ' type="checkbox" class="filled-in" name="on_condition[]" id="on_condition_' + i + '" ' + disabled + '/>'
	 			+			'<label for="on_condition_' + i + '">ON</label>'
	 			+		'</p>'
	 			+	'</div>'
	 			+	'<div class="col s4">'
	 			+		(!is_view ? '<a class="btn right m-n delete" id="remove_table_' + i + '" onclick="remove_table(' + i + ')"><i class="Small flaticon-minus102"></i> TABLE</a>' : '')
	 			+	'</div>'
	 			+ '</div>';
	 			var condition = group_hdr_dtl[i]['condition'];
	 			for(var j=0; j < condition.length; j++)
	 			{
	 				html += '<div class="note-info p-t-sm" id="table_row_' + i + '_condition_row_' + j +'">';
	 				if(j > 0) {
			 	html 	+=   '<div class="row">'
						+		'<div class="col s3">'
						+			'<select class="browser-default left operator" required="true" name="operator['+i+']['+j+']" id="operator" ' + disabled + '>'
						+				'<option value="" selected="">Select Operator</option>'
						+				'<option value="AND" ' + (condition[j]['operator'] == 'AND' ? 'selected' : '') + '>AND</option>'
						+				'<option value="OR" ' + (condition[j]['operator'] == 'OR' ? 'selected' : '') + '>OR</option>'
						+			'</select>'
						+		'</div>'
						+		'<div class="col s9">'
						+			'<a class="btn left m-n" onclick="remove_condition('+i+','+j+')"><i class="Small flaticon-minus102"></i> CONDITION</a>'
						+		'</div>'
						+	'</div>';
					}
	 	html 	+= '<div class="row p-t-sm">'
	 			+	'<div class="col s2">'
	 			+		'<select class="browser-default left table_name_a" onchange="get_table_fields(\'_a_\',\'' + i + '_' + j + '\')" required="true" name="alias_a['+i+']['+j+']" id="alias_a_' + i + '_' + j + '" ' + disabled + '>';	
	 				var prev_table_name = '';
	 				for(var x=0; x < data_dictionary.length; x++)
	 				{
	 					if(prev_table_name != data_dictionary[x]['table_name'])
	 					{
	 						var selected = '';
	 						if(condition[j]['first_table'] == data_dictionary[x]['table_name'])
	 							selected = 'selected';

	 	html +=				'<option value="' + data_dictionary[x]['table_name'] + '" ' + selected + '>' + data_dictionary[x]['table_name'] + '</option>';
	 						prev_table_name = data_dictionary[x]['table_name'];
	 					}
	 				}
	 	html +=			'</select>'
	 			+	'</div>'
	 			+	'<div class="col s3">'		
	 			+		'<select class="browser-default left field_name" required="true" name="field_a['+i+']['+j+']" id="field_a_' + i + '_' + j + '" ' + disabled + '>';
	 				for(var x=0; x < data_dictionary.length; x++)
	 				{
	 					if(condition[j]['first_table'] == data_dictionary[x]['table_name'])
	 					{
	 						var selected = '';
	 						if(data_dictionary[x]['column_name'] == condition[j]['first_field'])
	 							selected = 'selected';

	 	html +=				'<option value="' + data_dictionary[x]['column_name'] + '" ' + selected + '>' + data_dictionary[x]['column_name'] + '</option>';
	 					}
	 				}
	 	html +=			'</select>'
	 			+	'</div>'
	 			+	'<div class="left">'
	 			+		'<p>=</p>'
	 			+	'</div>'
	 			+	'<div class="col s2">'
	 			+		'<select class="browser-default left table_name_b" onchange="get_table_fields(\'_b_\',\'' + i + '_' + j + '\')" required="true" name="alias_b['+i+']['+j+']" id="alias_b_' + i + '_' + j + '" ' + disabled + '>';
	 				for(var x=0; x < data_dictionary.length; x++)
	 				{
	 					if(prev_table_name != data_dictionary[x]['table_name'])
	 					{
	 						var selected = '';
	 						if(condition[j]['second_table'] == data_dictionary[x]['table_name'])
	 							selected = 'selected';

	 	html +=				'<option value="' + data_dictionary[x]['table_name'] + '" ' + selected + '>' + data_dictionary[x]['table_name'] + '</option>';
	 						prev_table_name = data_dictionary[x]['table_name'];
	 					}
	 				}
	 	html +=			'</select>'
	 			+	'</div>'
	 			+	'<div class="col s3">'		
	 			+		'<select class="browser-default left field_name" required="true" name="field_b['+i+']['+j+']" id="field_b_' + i + '_' + j + '" ' + disabled + '>';
	 				for(var x=0; x < data_dictionary.length; x++)
	 				{
	 					if(condition[j]['second_table'] == data_dictionary[x]['table_name'])
	 					{
	 						var selected = '';
	 						if(data_dictionary[x]['column_name'] == condition[j]['second_field'])
	 							selected = 'selected';

	 	html +=				'<option value="' + data_dictionary[x]['column_name'] + '" ' + selected + '>' + data_dictionary[x]['column_name'] + '</option>';
	 					}
	 				}
	 	html +=			'</select>'
	 			+	'</div>'
	 			+ '</div>';
	 				if(j == 0) {
	 				html 	+=	'<div class="row p-t-sm">'
				 			+ 		'<div class="col s3">'
				 			+		  (!is_view ? '<a class="btn btn-success left m-n" name="condition[]" id="condition_' + i + '"><i class="Small flaticon-add175"></i> CONDITION</a>' : '')
				 			+ 		'</div>'
				 			+  	'</div>';
	 				}
	 			}
	 			html += '</div>';
		$('#appending_div').append(html);
		joining_select(i);
		on_condition(i);
		add_condition(i,condition.length);
	}		



}

// JOIN 
function joining_select(index)
{
	$('#join_'+index).change(function(){

		var join = $('#join_'+index).val();
	    if(join == 'LEFT JOIN' || join == 'RIGHT JOIN')
		{
			$('#on_condition_' + index).prop('checked',true);
	    	$('#table_row_' + index + ' .p-t-sm select').removeAttr('disabled');		
	    	$('#table_row_' + index + ' .p-t-sm a').removeClass('none');	
		}

	});
}

// CONDITIONS 
function on_condition(index)
{
	console.log(index);
	$('#on_condition_' + index).change(function()
	{
		if(this.checked) {
	    	$('#table_row_' + index + ' .p-t-sm select').removeAttr('disabled');	
	    	$('#table_row_' + index + ' .p-t-sm a').removeClass('none');	
	    } else {
	    	var join = $('#join_'+index).val();
	    	if(join == 'LEFT JOIN' || join == 'RIGHT JOIN') {
	    		$('#on_condition_' + index).prop('checked',true);
	    		notification_msg("<?php echo ERROR ?>", 'Cannot disable the conditions if the selected JOIN statement is either LEFT or RIGHT!');	
	    	} else {
	    		$('#table_row_' + index + ' .p-t-sm select').attr('disabled','');
	    		$('#table_row_' + index + ' .p-t-sm a').addClass('none');
	    	}
	    }
	});
    
}

function get_table_fields(initial, id)
{

	var selected_table  = document.getElementById('alias'+(initial+id)).value;
	
	var data_dictionary = <?php echo json_encode($data_dictionary) ?>;
	var result          = '';

	for (var i=0; i < data_dictionary.length; i++)
	{
		if(data_dictionary[i]['table_name'] == selected_table)
		{
			result += '<option value="' +  data_dictionary[i]['column_name'] + '"  >' + data_dictionary[i]['column_name'] + '</option>';

		}
	}

	$('#field' + initial + id).html(result);
}


function add_condition(table_row,row_index)
{
	$('#condition_' + table_row).on('click', function()
	{
		var clonerow = $("#add_condition");

		// clone the row
		clonerow.clone().attr("id", "table_row_" + table_row + "_condition_row_" + row_index).removeAttr("style").appendTo("#table_row_"+table_row);
		// handleSelect2();

		var newrow = $("#table_row_" + table_row + "_condition_row_" + row_index);

		// assign id and name to selectize of newly created row 
		newrow.find('select.table_name_a').each(function(){
			var myvar = $(this).attr("id") + '_' + table_row + '_' + row_index;
			var myname = $(this).attr("id") + "["+table_row+"]" + "["+row_index+"]";
			$(this).attr({
				name: myname,
				id: myvar,
				onchange: 'get_table_fields(\'_a_\',\'' + table_row + '_' + row_index + '\')'
			}).val('');
		});

		newrow.find('select.table_name_b').each(function(){
			var myvar = $(this).attr("id") + '_' + table_row + '_' + row_index;
			var myname = $(this).attr("id") + "["+table_row+"]" + "["+row_index+"]";
			$(this).attr({
				name: myname,
				id: myvar,
				onchange: 'get_table_fields(\'_b_\',\'' + table_row + '_' + row_index + '\')'
			}).val('');
		});

		newrow.find('select.field_name').each(function(){
			var myvar = $(this).attr("id") + '_' + table_row + '_' + row_index;
			var myname = $(this).attr("id") + "["+table_row+"]" + "["+row_index+"]";
			$(this).attr({
				name: myname,
				id: myvar
			}).val('');
		});

		newrow.find('select.operator').each(function(){
			var myvar = $(this).attr("id") + '_' + table_row + '_' + row_index;
			var myname = $(this).attr("id") + "["+table_row+"]" + "["+row_index+"]";
			$(this).attr({
				name: myname,
				id: myvar
			}).val('');
		});


		newrow.find('a').attr({
			id: "table_row_" + table_row + "_remove_condition_" + row_index ,
			onclick: "remove_condition("+ table_row + "," +row_index+")"
		});

		row_index++; 
	
	});
}



function remove_table(row_index)
{
	$("#table_row_" + row_index).remove();
}

function remove_condition(table_row,row_index)
{
	$("#table_row_" + table_row + "_condition_row_" + row_index).remove();
}

</script>

<!-- DIV FOR ADDING CONDITIONS -->
<!-- START -->
<div class="note-info p-t-sm" style="display:none" id=add_condition>
	<div class="row">
		<div class="col s3">
			<select class="browser-default left operator" required="true" name="operator[][]" id="operator">
				<option value="" selected="">Select Operator</option>
				<option value="AND">AND</option>
				<option value="OR">OR</option>
			</select>
		</div>
		<div class="col s9">
			<a class="btn left m-n" ><i class="Small flaticon-minus102"></i> CONDITION</a>
		</div>
	</div>
	<div class="row p-t-sm">
		<div class="col s2">
			<select class="browser-default left table_name_a" required="true" name="alias_a[]" id="alias_a">
				<option value="">Select table</option>
				<?php
				foreach($data_dictionary AS $table) :
					if($table['table_name'] != $prev_table_name)
					echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
					$prev_table_name = $table['table_name'];
					$main_prev_table_name = $table['table_name'];
				endforeach;
				?>
			</select>
		</div>
		<div class="col s3">
			<select class="browser-default left field_name" required="true" name="field_a[]" id="field_a">
				<option value="">No fields found.</option>
			</select>
		</div>
		<div class="left">
			<p>=</p>
		</div>
		<div class="col s2">
			<select class="browser-default left table_name_b" required="true" name="alias_b[]" id="alias_b">
				<option value="">Select table</option>
				<?php
				foreach($data_dictionary AS $table) :
					if($table['table_name'] != $prev_table_name)
					echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
					$prev_table_name = $table['table_name'];
					$main_prev_table_name = $table['table_name'];
				endforeach;
				?>
			</select>
		</div>
		<div class="col s3">
			<select class="browser-default left field_name" required="true" name="field_b[]" id="field_b">
				<option value="">No fields found.</option>
			</select>
		</div>
	</div>
</div>

<!-- END -->

<!-- DIV FOR ADDING TABLE ROW -->
<!-- START -->
<div class="list-group-item" style="display:none" id=add_row>
	<div class="row">
		<div class="col s2">
			<select class="browser-default left join" required="true" name="join[]" id="join">
				<option value="" selected="">Select Join</option>
				<option value="JOIN">JOIN</option>
				<option value="LEFT JOIN">LEFT JOIN</option>
				<option value="RIGHT JOIN">RIGHT JOIN</option>
			</select>
		</div>
		<div class="col s4">
			<select class="browser-default left" required="true" name="join_table[]" id="join_table">
				<option value="" selected="">Select Table</option>
				<?php
					foreach($data_dictionary AS $table) :
						if($table['table_name'] != $prev_table_name)
						echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
						$prev_table_name = $table['table_name'];
					endforeach;

				?>
			</select>
		</div>
		<div class="col s2">
			<p>
				<input type="checkbox" class="filled-in" name="on_condition[]" id="on_condition">
				<label for="on_condition_">ON</label>
			</p>
		</div>
		<div class="col s4">
			<a class="btn right m-n delete" id="remove_div"><i class="Small flaticon-minus102"></i> TABLE</a>
		</div>
	</div>
	<div class="row p-t-sm">
		<div class="col s2">
			<select class="browser-default left table_name_a" required="true"" disabled name="alias_a[]" id="alias_a">
				<option value="">Select table</option>
				<?php
				foreach($data_dictionary AS $table) :
					if($table['table_name'] != $prev_table_name)
					echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
					$prev_table_name = $table['table_name'];
					$main_prev_table_name = $table['table_name'];
				endforeach;
				?>
			</select>
		</div>
		<div class="col s3">
			<select class="browser-default left field_name" required="true" disabled name="field_a[]" id="field_a">
				<option value="">No fields found.</option>
			</select>
		</div>
		<div class="left">
			<p>=</p>
		</div>
		<div class="col s2">
			<select class="browser-default left table_name_b" required="true"" disabled name="alias_b[]" id="alias_b">
				<option value="">Select table</option>
				<?php
				foreach($data_dictionary AS $table) :
					if($table['table_name'] != $prev_table_name)
					echo '<option value="' . $table['table_name'] . '" >' . $table['table_name'] . '</option>';
					$prev_table_name = $table['table_name'];
					$main_prev_table_name = $table['table_name'];
				endforeach;
				?>
			</select>
		</div>
		<div class="col s3">
			<select class="browser-default left field_name" required="true" disabled="" name="field_b[]" id="field_b">
				<option value="">No fields found.</option>
			</select>
		</div>
	</div>
	<div class="row p-t-sm">
		<div class="col s3">
			<a class="btn btn-success left m-n add_condition none" name="condition[]" id="condition"><i class="Small flaticon-add175"></i> CONDITION</a>
		</div>
	</div>
</div>
<!-- END -->