<?php
$id = !EMPTY($wp["process_id"])? $wp["process_id"] : "";
$num_stages = (!EMPTY($wp["num_stages"]))? $wp["num_stages"] : "";

$process_id = !EMPTY($process_id)? $process_id : "";
$salt = gen_salt();
$token = in_salt($id, $salt);
?>
<div class="row m-t-lg">
  <div class="col l9 m12 s12">
	<form id="workflow_steps_form" class="m-t-md">
	  <input type="hidden" name="id" value="<?php echo $process_id ?>">
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  <input type="hidden" name="num_stages" value="<?php echo count($ws) ?>">
	  <div class="none" id="deleted_steps"></div>
	  <div class="form-basic">
		<div id="site-info" class="scrollspy table-display input-field white box-shadow">
			<div class="table-cell bg-dark p-lg valign-top" style="width:25%">
				<label class="label mute">Process Steps</label>
				<p class="caption m-t-sm white-text">
					Define steps for every stage of each process. Every steps included in a stage should have a corresponding status that will be used for tracking.
				</p>
			</div>
			
			<!-- USED FOR ADDING ROWS -->
			<table>
			<tr id="workflow_steps_row" style="display:none!important">
			  <td width="55%"><div class="input-field m-n"><input type="text" id="step_name" value=""/></div></td>
			  <td width="30%">
				<select id="step_status" class="selectize">
					<?php foreach ($status as $stat): ?>
						<option value="<?php echo $stat["status_id"] ?>"><?php echo $stat["status"] ?></option>
					<?php endforeach ?>
				</select>
			  </td>
			  <td width="15%" class="center-align"><a href="javascript:;"><img src="<?php echo base_url().PATH_IMAGES ?>trash.png"></a></td>
			</tr>
			</table>
		
			<div class="table-cell valign-top">
			<?php 
			if(!EMPTY($ws)){
				for($i=0; $i<count($ws); $i++){ 
					$j = 1;
					$steps_id = (!EMPTY($ws[$i]["steps_id"]))? explode(",",$ws[$i]["steps_id"]) : array();
					$steps_name = explode(",",$ws[$i]["steps_name"]);
					$steps_status = explode(",",$ws[$i]["steps_status"]);
					$steps_cnt = (!EMPTY($steps_id))? count($steps_id) : 1;
				?>
					<input type="hidden" name="step_cnt[<?php echo $ws[$i]["process_stage_id"] ?>]" id="workflow_steps_cnt_<?php echo $ws[$i]["process_stage_id"] ?>" value="<?php echo $steps_cnt ?>">
					<p class="font-md m-t-md font-thin blue-grey-text text-darken-4 m-l-md font-semibold">Stage : <?php echo $ws[$i]["name"] ?></p>
					
					<table class="table table-default b-t" style="border-color:#e3e8ed!important;">
					  <thead>
						<tr>
						  <th width="55%">Steps</th>
						  <th width="30%">Status</th>
						  <th width="15%"></th>
						</tr>
					  </thead>
					</table>
					<table class="table table-default font-sm b-b" style="border-color:#e3e8ed!important" cellspacing="0" cellpadding="0" width="100%">
					  <tbody id="workflow_steps_div_<?php echo $ws[$i]["process_stage_id"] ?>">
						<?php for($j=1; $j<=$steps_cnt; $j++){ ?>
						<tr id="workflow_steps_row_<?php echo $ws[$i]["process_stage_id"]."_".$j ?>">
						  <td width="55%">
							<div class="input-field m-n">
								<input type="text" name="step_name[<?php echo $ws[$i]["process_stage_id"] ?>][<?php echo $j ?>]" id="step_name_<?php echo $ws[$i]["process_stage_id"]."_".$j ?>" value="<?php echo $steps_name[$j-1] ?>"/>
							</div>
						  </td>
						  <td width="30%">
							<select name="step_status[<?php echo $ws[$i]["process_stage_id"] ?>][<?php echo $j ?>]" id="step_status_<?php echo $ws[$i]["process_stage_id"]."_".$j ?>" class="selectize">
								<option value=""></option>
								<?php foreach ($status as $stat): 
									$selected = ($stat["status_id"] == $steps_status[$j-1])? "selected" : "";
								?>
									<option value="<?php echo $stat["status_id"] ?>" <?php echo $selected ?>><?php echo $stat["status"] ?></option>
								<?php endforeach ?>
							</select>
						  </td>
						  <td width="15%">
						  <?php if($j > 1){ ?>
							<div class="input-field center-align">
							  <a href="javascript:;" onclick="remove_workflow_steps('<?php echo $ws[$i]["process_stage_id"] ?>', '<?php echo $j ?>')"><img src="<?php echo base_url().PATH_IMAGES ?>trash.png"></a></div>
							</div>	
						  <?php } ?>	
						  </td>
						</tr>
						<?php } ?>
					  </tbody>
					</table>
					<div class="m-t-md m-r-md m-b-md right-align" id="add_step_<?php echo $ws[$i]["process_stage_id"] ?>">
					  <a href="javascript:;" onclick="add_step('<?php echo $ws[$i]["process_stage_id"] ?>', '<?php echo $steps_cnt ?>')">&#43; Add Step</a>
					</div>
			<?php 
				}
			} else {
			?>	
				<div class="row">
					<div class="col s12 input-field">
						Process stage is required before proceeding to process steps</p>
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
			  <button class="btn  bg-success" type="button" id="save_workflow_steps"><?php echo BTN_SAVE ?></button>
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
	  <li>Steps</li>
	  <li>Actions</li>
	</ul>
  </div>
</div>

<script type="text/javascript">
$(function(){
	$('.tooltipped').tooltip({delay: 50});
	
	var saveObj = new handleData({ controller : 'workflow_steps', method : 'process', module : '<?php echo PROJECT_CORE ?>' });
	
	$("#save_workflow_steps").on("click", function(){
		var data = $("#workflow_steps_form").serialize();
		saveObj.updateData(data);
	});
});

function add_step(stage_id, row_index){
	var clonerow = $("#workflow_steps_row");
		
	row_index++; 
	$("#workflow_steps_cnt_" + stage_id).val(row_index);
	
	clonerow.find('select').each(function() {		
		if ($(this)[0].selectize) {
			// Destroy the selectize.js element
			$(this)[0].selectize.destroy();
		}
	});
	
	// clone the row
	clonerow.clone().attr("id", "workflow_steps_row_" + stage_id + "_" + row_index).removeAttr("style").appendTo("#workflow_steps_div_" + stage_id);
	
	var newrow = $("#workflow_steps_row_" + stage_id + "_" + row_index);
	
	// assign id and name to selectize of newly created row 
	newrow.find('select').each(function(){
		var myvar = $(this).attr("id") + "_" + stage_id + "_" + row_index;
		var myname = $(this).attr("id") + "["+stage_id+"]" + "["+row_index+"]";

		$(this).attr({
			name: myname,
			id: myvar
		}).val('').selectize();
		$(this)[0].selectize.enable();
	});

	newrow.find('input').each(function(){
		var myvar = $(this).attr("id") + "_" + stage_id + "_" + row_index;
		var myname = $(this).attr("id") + "["+stage_id+"]" + "["+row_index+"]";

		$(this).attr({
			name: myname,
			id: myvar
		}).val('');
	});

	newrow.find('a').attr({
		id: "remove_workflow_steps_" + stage_id + "_" + row_index ,
		onclick: "remove_workflow_steps('"+stage_id+"','"+row_index+"')"
	});
	
	$("#add_step_" + stage_id).find('a').attr({
		onclick: "add_step('"+stage_id+"','"+row_index+"')"
	});
}

function remove_workflow_steps(stage_id, row_index){
	$("#workflow_steps_row_" + stage_id + "_" + row_index).remove();
}
</script>