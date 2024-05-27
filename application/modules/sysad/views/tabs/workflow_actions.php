<?php
$id = !EMPTY($wp["process_id"])? $wp["process_id"] : "";
$num_stages = (!EMPTY($wp["num_stages"]))? $wp["num_stages"] : "";

$process_id = !EMPTY($process_id)? $process_id : "";
$salt = gen_salt();
$token = in_salt($id, $salt);
?>
<div class="m-t-lg">
  <div class="row">
  <div class="col l9 m12 s12">
	<form id="workflow_actions_form" class="m-t-md">
	  <input type="hidden" name="id" value="<?php echo $process_id ?>">
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  <input type="hidden" name="num_stages" value="<?php echo count($wa) ?>">
	  <div class="form-basic">
		<div id="site-info" class="scrollspy table-display input-field white box-shadow">
			<div class="table-cell bg-dark p-lg valign-top" style="width:25%">
				<label class="label mute">Process Actions</label>
				<p class="caption m-t-sm white-text">
					Define actions for every process steps. Actions act as the main trigger for a process to continue, 
					depending on the specified proceeding step.
				</p>
			</div>
		
			<div class="table-cell valign-top" style="width:75%">
				<!-- USED FOR ADDING ROWS -->
				<table>
				<tr id="workflow_actions_row" style="display:none!important">
				  <td width="25%"><div class="input-field m-n"><input type="text" id="action_name" value=""/></div></td>
				  <td width="20%">
					<div class="input-field m-n">
					  <select id="step_id" class="selectize">
						<?php foreach ($steps as $step): ?>
						  <option value="<?php echo $step["process_step_id"] ?>"><?php echo $step["name"] ?></option>
						<?php endforeach ?>
					  </select>
					</div>
				  </td>
				  <td width="20%"><div class="input-field m-n"><textarea name="message" id="message" class="materialize-textarea"></textarea></div></td>
				  <td width="20%"><input type="checkbox" class="labelauty" data-labelauty="No|Yes" name="update_db_flag" id="update_db_flag" value="1"></td>
				  <td width="15%" class="center-align"><div class="input-field"><a href="javascript:;"><img src="<?php echo base_url().PATH_IMAGES ?>trash.png"></a></div></td>
				</tr>
				</table>
				
			  <?php 
			  if(!EMPTY($wa)){
				for($i=0; $i<count($wa); $i++){ 
				
				  $steps_id = explode(",",$wa[$i]["steps_id"]);
				  $steps_name = explode(",",$wa[$i]["steps_name"]);
				  $steps_status = explode(",",$wa[$i]["steps_status"]);
				  $steps_cnt = (!EMPTY($steps_id))? count($steps_id) : 1;
				
				  $actions_id = explode(",",$wa[$i]["actions_id"]);
				  $actions_name = explode(",",$wa[$i]["actions_name"]);
				  $proceeding_steps = explode(",",$wa[$i]["proceeding_steps"]);
				  $actions_cnt = (!EMPTY($actions_id))? count($actions_id) : 1;
				  $message = explode(",",$wa[$i]["message"]);
				  $update_db_flag = explode(",",$wa[$i]["update_db_flag"]);
				  $stage = "<p class='font-md m-t-md font-thin blue-grey-text text-darken-4 m-l-md font-semibold'>Stage : ".$wa[$i]["name"]."</p>";
				  
				  if($i > 0){
					  $stage = ($wa[$i]["process_stage_id"] != $wa[$i-1]["process_stage_id"])? "<p class='font-md m-t-md font-thin blue-grey-text text-darken-4 m-l-md font-semibold'>Stage : ".$wa[$i]["name"]."</p>" : "";
				  } 
				
				  echo $stage;	
			  ?>
			  
			  <?php for($j=1; $j<=$steps_cnt; $j++){ ?>		
				<input type="hidden" name="action_cnt[<?php echo $wa[$i]["process_stage_id"] ?>][<?php echo $steps_id[$j-1] ?>]" id="workflow_actions_cnt_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1] ?>" value="<?php echo $actions_cnt ?>">
			    <div class="m-t-sm m-b-md m-l-md font-sm">
				  <span class="p-t-sm inline font-semibold">Step : <?php echo $steps_name[$j-1] ?></span>
				  <span class="badge red darken-1 white-text font-xs p-xs"><?php echo $steps_status[$j-1] ?></span>
			    </div>
					
			    <table class="table table-default b-t" style="border-color:#e3e8ed!important;">
			    <thead>
				  <tr>
 				    <th width="22%">Actions</th>
				    <th width="20%">Next Step</th>
				    <th width="30%">Message</th>
				    <th width="20%">Update DB?</th>
				    <th width="8%"></th>
				  </tr>
			    </thead>
			    </table>
						
				<table class="table table-default font-sm b-b" style="border-color:#e3e8ed!important" cellspacing="0" cellpadding="0" width="100%">
				<tbody id="workflow_actions_div_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1] ?>">
				  <?php for($k=1; $k<=$actions_cnt; $k++){ 
						$checked = ($update_db_flag[$k-1])? "checked" : "";
				  ?>
					<tr id="workflow_actions_row_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1]."_".$k ?>">
					  <td width="22%">
						<div class="input-field m-n">
						  <input type="text" name="action_name[<?php echo $wa[$i]["process_stage_id"] ?>][<?php echo $steps_id[$j-1] ?>][<?php echo $k ?>]" id="step_name_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1]."_".$k ?>" value="<?php echo $actions_name[$k-1] ?>"/>
						</div>
					  </td>
					  <td width="20%">
						<div class="input-field m-n">
						  <select name="step_id[<?php echo $wa[$i]["process_stage_id"]?>][<?php echo $steps_id[$j-1] ?>][<?php echo $k ?>]" id="step_id_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1]."_".$k ?>" class="selectize">
							<option value=""></option>
							<?php foreach ($steps as $step): 
							  $selected = ($step["process_step_id"] == $proceeding_steps[$k-1])? "selected" : "";
							?>
							  <option value="<?php echo $step["process_step_id"] ?>" <?php echo $selected ?>><?php echo $step["name"] ?></option>
							<?php endforeach; ?>
						  </select>
						</div>
					  </td>
					  <td width="30%">
						<div class="input-field m-n">
						  <textarea name="message[<?php echo $wa[$i]["process_stage_id"] ?>][<?php echo $steps_id[$j-1] ?>][<?php echo $k ?>]" id="message_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1]."_".$k ?>" class="materialize-textarea"><?php echo $message[$k-1] ?></textarea>
						</div>
					  </td>
					  <td width="20%">
						<input type="checkbox" class="labelauty" data-labelauty="No|Yes" name="update_db_flag[<?php echo $wa[$i]["process_stage_id"] ?>][<?php echo $steps_id[$j-1] ?>][<?php echo $k ?>]" value="1" <?php echo $checked ?>>
					  </td>
					  <td width="8%">
						<?php if($k > 1){ ?>
						  <div class="input-field center-align">
							<a href="javascript:;" onclick="remove_workflow_actions('<?php echo $wa[$i]["process_stage_id"] ?>', '<?php echo $steps_id[$j-1] ?>', '<?php echo $k ?>')"><img src="<?php echo base_url().PATH_IMAGES ?>trash.png"></a>
						  </div>
						<?php } ?>
					  </td>
					</tr>
					<?php } ?>
						  </tbody>
						</table>
						<div class="m-t-md m-r-md m-b-md right-align" id="add_action_<?php echo $wa[$i]["process_stage_id"]."_".$steps_id[$j-1] ?>">
							<a href="javascript:;" onclick="add_action('<?php echo $wa[$i]["process_stage_id"] ?>', '<?php echo $steps_id[$j-1] ?>', '<?php echo $actions_cnt ?>')">&#43; Add Action</a>
						</div>
			  <?php 
				  }
				}
			  } else {
			  ?>	
					<div class="row">
						<div class="col s12 input-field">
							Process steps are required before proceeding to process steps</p>
						</div>	
					</div>
			<?php 			
			}
			?>
			</div>
		  </div>
		</div>
		<div class="panel-footer right-align">
		    <div class="input-field inline m-n">
			  <button class="btn  bg-success" type="button" id="save_workflow_actions"><?php echo BTN_SAVE ?></button>
		    </div>
		</div>
	</form>
  </div>
  
  <div class="col l3 m12 s12 p-l-lg">
    <div class="m-t-lg">
	  <p class="font-bold font-lg"><?php echo $wp['name'] ?></p>
	  <p class="font-normal"><?php echo $wp['description'] ?></p>
	</div>
    <ul class="list-timeline m-t-md">
	  <li class="complete">Process
	    <ul>
		  <li class="tooltipped cursor-pointer" data-delay="50" data-position="bottom" data-tooltip="<?php echo $wp['name'] ?>">Name</li>
		  <li class="tooltipped cursor-pointer" data-delay="50" data-position="bottom" data-tooltip="This process has <?php echo $wp['num_stages'] ?> stage(s)">No. of Stages</li>
		  <li class="tooltipped cursor-pointer" data-delay="50" data-position="bottom" data-tooltip="<?php echo $wp['description'] ?>">Description</li>
		</ul>
	  </li>
	  <li class="complete">Stage
	    <ul class="stage">
		  <?php foreach($wstage as $stage): ?>
		    <li class="tooltipped cursor-pointer" data-delay="50" data-position="bottom" data-tooltip="<?php echo $stage['role_name'] ?>"><?php echo $stage['name'] ?></li>
		  <?php endforeach; ?>
		</ul>
	  </li>
	  <li class="complete">Steps
	    <ul class="stage">
		  <?php foreach($ws as $steps): ?>
		    <li class="tooltipped cursor-pointer" data-delay="50" data-position="bottom" data-tooltip="Steps in <?php echo $steps['name'] ?> Stage"><?php echo $steps['steps_name'] ?></li>
		  <?php endforeach; ?>
		</ul>
	  </li>
	  <li>Actions</li>
	</ul>
  </div>
</div>
</div>

<script type="text/javascript">
$(function(){
	var saveObj = new handleData({ controller : 'workflow_actions', method : 'process', module : '<?php echo PROJECT_CORE ?>' });
	
	$("#save_workflow_actions").on("click", function(){
		var data = $("#workflow_actions_form").serialize();
		saveObj.updateData(data);
	});
	
	$('.tooltipped').tooltip({delay: 50});
});

function add_action(stage_id, step_id, row_index){
	
	var clonerow = $("#workflow_actions_row");
		
	row_index++; 
	$("#workflow_actions_cnt_" + stage_id + "_" + step_id).val(row_index);
	
	clonerow.find('select').each(function() {		
		if ($(this)[0].selectize) {
			// Destroy the selectize.js element
			$(this)[0].selectize.destroy();
		}
	});
	
	// clone the row
	clonerow.clone().attr("id", "workflow_actions_row_" + stage_id + "_" + step_id + "_" + row_index).removeAttr("style").appendTo("#workflow_actions_div_" + stage_id + "_" + step_id);
	
	var newrow = $("#workflow_actions_row_" + stage_id + "_" + step_id + "_" + row_index);
	
	// assign id and name to selectize of newly created row 
	newrow.find('select').each(function(){
		var myvar = $(this).attr("id") + "_" + stage_id + "_" + step_id + "_" + row_index;
		var myname = $(this).attr("id") + "["+stage_id+"]" + "["+step_id+"]" + "["+row_index+"]";

		$(this).attr({
			name: myname,
			id: myvar
		}).val('').selectize();
		$(this)[0].selectize.enable();
	});

	newrow.find('input').each(function(){
		var myvar = $(this).attr("id") + "_" + stage_id + "_" + step_id + "_" + row_index;
		var myname = $(this).attr("id") + "["+stage_id+"]" + "["+step_id+"]" + "["+row_index+"]";

		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
		
		if ($(this).hasClass("labelauty")) {
			$(this).labelauty({
				checked_label: "Yes",
				unchecked_label: "No",
			});
		}
	});
	
	newrow.find('textarea').each(function(){
		var myvar = $(this).attr("id") + "_" + stage_id + "_" + step_id + "_" + row_index;
		var myname = $(this).attr("id") + "["+stage_id+"]" + "["+step_id+"]" + "["+row_index+"]";

		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
	});

	newrow.find('a').attr({
		id: "remove_workflow_actions_" + stage_id + "_" + step_id + "_" + row_index ,
		onclick: "remove_workflow_actions('"+stage_id+"','"+step_id+"','"+row_index+"')"
	});
	
	$("#add_action_" + stage_id + "_" + step_id).find('a').attr({
		onclick: "add_action('"+stage_id+"','"+step_id+"','"+row_index+"')"
	});
}

function remove_workflow_actions(stage_id, step_id, row_index){
	$("#workflow_actions_row_" + stage_id + "_" + step_id + "_" + row_index).remove();
}
</script>