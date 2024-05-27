<?php
$id = !EMPTY($wp["process_id"])? $wp["process_id"] : "";
$process_name = !EMPTY($wp["name"])? $wp["name"] : "";
$process_desc = !EMPTY($wp["description"])? $wp["description"] : "";
$num_stages = !EMPTY($wp["num_stages"])? $wp["num_stages"] : "";	

$process_id = !EMPTY($process_id)? $process_id : "";
$salt = gen_salt();
$token = in_salt($id, $salt);
?>

<div class="row m-t-lg">
  <div class="col l9 m12 s12">
	<form id="workflow_process_form" class="m-t-lg">
	  <input type="hidden" name="id" value="<?php echo $process_id ?>">
	  <input type="hidden" name="salt" value="<?php echo $salt ?>">
	  <input type="hidden" name="token" value="<?php echo $token ?>">
	  <div class="form-basic">
		<div id="site-info" class="scrollspy table-display input-field white box-shadow">
		  <div class="table-cell bg-dark p-lg valign-top" style="width:25%">
			<label class="label mute">Process Details</label>
			<p class="caption m-t-sm white-text">Control how your approval system will flow by creating a dynamic process.</p>
		  </div>
		  <div class="table-cell p-lg valign-top">
			<div class="row">
			  <div class="col s9">
				<div class="input-field">
				  <input type="text" name="process_name" id="process_name" class="validate" required="" aria-required="true" value="<?php echo $process_name ?>"/>
				  <label for="process_name" <?php if(!EMPTY($process_name)){ ?>class="active"<?php } ?>>Name</label>
				</div>
			  </div>
			  <div class="col s3">
				<div class="input-field">
				  <input type="text" name="num_stages" id="num_stages" value="<?php echo $num_stages ?>"/>
				  <label for="num_stages" <?php if(!EMPTY($num_stages)){ ?>class="active"<?php } ?>>No. of stages</label>
				</div>
			  </div>
			  <div class="col s12 m-t-md">
				<div class="input-field">
				  <textarea id="process_desc" name="process_desc" class="materialize-textarea"><?php echo $process_desc ?></textarea>
				  <label for="process_desc" <?php if(!EMPTY($process_desc)){ ?>class="active"<?php } ?>>Description</label>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		<div class="panel-footer right-align">
		    <div class="input-field inline m-n">
			  <button class="btn  bg-success" type="button" id="save_workflow_process"><?php echo BTN_SAVE ?></button>
		    </div>
		</div>
	  </div>
	</form>
  </div>
  <div class="col l3 m12 s12 p-l-lg">
    <div class="m-t-lg">
	  <p class="font-bold font-lg"><?php echo $process_name ?></p>
	  <p class="font-normal"><?php echo $process_desc ?></p>
	</div>
    <ul class="list-timeline m-t-md">
	  <li>Process</li>
	  <li>Stage</li>
	  <li>Steps</li>
	  <li>Actions</li>
	</ul>
  </div>
</div>

<script type="text/javascript">
$(function(){
	$("#save_workflow_process").on("click", function(){
		var data = $("#workflow_process_form").serialize();
		
		$.post("<?php echo base_url().PROJECT_CORE ?>/workflow_process/process", data, function(result){
			var type = (result.flag == 1) ? "success" : "error";
			notification_msg(type, result.msg);
			
			if(result.process_id)
				window.location.href = "<?php echo base_url().PROJECT_CORE ?>/manage_workflow/create/" + result.process_id;
		}, 'json');
	});
});
</script>