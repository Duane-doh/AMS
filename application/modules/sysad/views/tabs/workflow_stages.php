<?php
$id = !EMPTY($wp["process_id"])? $wp["process_id"] : "";
$num_stages = (!EMPTY($wp["num_stages"]))? $wp["num_stages"] : "";

$process_id = !EMPTY($process_id)? $process_id : "";
$salt = gen_salt();
$token = in_salt($id, $salt);
?>

<div class="row m-t-lg">
  <div class="col l9 m12 s12">
	<form id="workflow_stages_form" class="m-t-md">
	  <input type="hidden" name="id" value="<?php echo $process_id ?>">
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  <input type="hidden" name="num_stages" value="<?php echo $num_stages ?>">
	  <div class="form-basic">
		<div id="site-info" class="scrollspy table-display input-field white box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Process Stages</label>
			<p class="caption m-t-sm white-text">
				Define the stages for each process. Stages act as a primary division of a process.
				Assign system roles for each stage to do that specific task.
			</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<?php 
				if(!EMPTY($num_stages)){
					for($i=1; $i<=$num_stages; $i++){ 
						if(!EMPTY($ws[$i-1]["roles"])){
							$stage_roles = explode(",",$ws[$i-1]["roles"]);
							foreach ($stage_roles as $stage_role):	
			?>		
								<script type="text/javascript">
									$("#stage_role_<?php echo $i ?>")[0].selectize.addItem("<?php echo $stage_role ?>");
								</script>	
			<?php			
							endforeach; 
						}	
			?>
				<p class="font-md font-semibold blue-grey-text text-darken-4">STAGE <?php echo $i ?></p>
				<div class="row m-l-n m-b-xl">
				  <div class="col s12 p-n">
				    <div class="input-field">
					  <input type="text" name="stage_name[<?php echo $i ?>]" id="stage_name_<?php echo $i ?>" value="<?php echo !EMPTY($ws[$i-1]["name"])? $ws[$i-1]["name"] : ""; ?>"/>
					  <label for="stage_name_<?php echo $i ?>" <?php if(!EMPTY($ws[$i-1]["name"])){ ?>class="active"<?php } ?>>Name</label>
				    </div>
				  </div>
				  <div class="col s12 p-n">
				    <div class="input-field">
					  <label for="stage_role_<?php echo $i ?>" class="active">Role</label>
 					  <select name="stage_role[<?php echo $i ?>][]" id="stage_role_<?php echo $i ?>" class="selectize" placeholder="Select a role" multiple>
						<option value=""></option>	
						<?php foreach ($roles as $role): ?>
							<option value="<?php echo $role["role_code"] ?>"><?php echo $role["role_name"] ?></option>
						<?php endforeach; ?>
					  </select>
				    </div>
				  </div>
				</div>
			<?php 
					} 
				}
			?>
		  </div>
		</div>
		<div class="panel-footer right-align">
		    <div class="input-field inline m-n">
			  <button class="btn  bg-success" type="button" id="save_workflow_stages"><?php echo BTN_SAVE ?></button>
		    </div>
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
	  <li>Stage</li>
	  <li>Steps</li>
	  <li>Actions</li>
	</ul>
  </div>
</div>

<script type="text/javascript">
$(function(){
	var saveObj = new handleData({ controller : 'workflow_stages', method : 'process', module : '<?php echo PROJECT_CORE ?>' });
	
	$("#save_workflow_stages").on("click", function(){
		var data = $("#workflow_stages_form").serialize();
		saveObj.updateData(data);
	});
	
	$('.tooltipped').tooltip({delay: 50});
});
</script>