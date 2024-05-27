<?php $disabled = (EMPTY($process_id))? "disabled" : ""; ?>
<section id="content" class="p-t-n m-t-n">        
  <!--breadcrumbs start-->
  	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
	  <div class="container">
	    <div class="row">
	      <div class="col s12 m12 l12">
	        <h5 class="breadcrumbs-title">Create Workflow</h5>
	        <ol class="breadcrumb m-n p-b-sm">
	             <?php get_breadcrumbs();?>
	        </ol>
	        
	      </div>
	    </div>
	  </div>
	</div>

<div class="tabs-wrapper">
  <div>
    <ul class="tabs teal">
	  <li class="tab col s3"><a class="active" href="#tab_workflow_process" onclick="load_index('tab_workflow_process', 'workflow_process/tab/<?php echo $process_id ?>', '<?php echo PROJECT_CORE ?>')">Process</a></li>
	  <li class="tab col s3 <?php echo $disabled ?>"><a <?php if(!EMPTY($process_id)){ ?>href="#tab_workflow_stages" onclick="load_index('tab_workflow_stages', 'workflow_stages/tab/<?php echo $process_id ?>', '<?php echo PROJECT_CORE ?>')"<?php } ?>>Stages</a></li>
	  <li class="tab col s3 <?php echo $disabled ?>"><a <?php if(!EMPTY($process_id)){ ?>href="#tab_workflow_steps" onclick="load_index('tab_workflow_steps', 'workflow_steps/tab/<?php echo $process_id ?>', '<?php echo PROJECT_CORE ?>')"<?php } ?>>Steps</a></li>
	  <li class="tab col s3 <?php echo $disabled ?>"><a <?php if(!EMPTY($process_id)){ ?>href="#tab_workflow_actions" onclick="load_index('tab_workflow_actions', 'workflow_actions/tab/<?php echo $process_id ?>', '<?php echo PROJECT_CORE ?>')"<?php } ?>>Actions</a></li>
    </ul>
  </div>
</div>

  <div id="tab_workflow_process" class="tab-content col s12"></div>
  <div id="tab_workflow_stages" class="tab-content col s12"></div>
  <div id="tab_workflow_steps" class="tab-content col s12"></div>
  <div id="tab_workflow_actions" class="tab-content col s12"></div>
 </section>
<script type="text/javascript">
$(function(){
	var path = "<?php echo base_url().PROJECT_CORE ?>/workflow_process/tab/<?php echo $process_id ?>";
	$("#tab_workflow_process").load(path).fadeIn("slow").show();
});
</script>