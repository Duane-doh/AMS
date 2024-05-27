
<!-- START CONTENT -->
      <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s8 m8 l8">
                <h5 class="breadcrumbs-title">Workflow Management</h5>
                <ol class="breadcrumb m-n p-b-sm">
                    <?php get_breadcrumbs();?>
                </ol>
              </div>
              <div class="col s4 p-r-n p-t-lg right-align">
			  
			  <div class="input-field inline p-l-md">
			    <button class="btn  btn-success" name="add_user" id="add_user" type="button" onclick="content_form('manage_workflow/create#tab_workflow_process', '<?php echo PROJECT_CORE ?>')">Add Workflow</button>
			  </div>
			</div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        
        <!--start container-->
        <div class="container">
          <div class="section panel p-lg">
      <!--start section-->
      		<div class="pre-datatable"></div>
			<div>
			  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="workflow_table">
			  <thead>
				<tr class="row-subheader">
				  <th width="8%" class="center-align">ID</th>
				  <th width="25%">Name</th>
				  <th width="30%">Description</th>
				  <th width="10%" class="center-align">No. Stages</th>
				  <th width="15%">Created By</th>
				  <th width="12%" class="center-align">Actions</th>
				</tr>
         <tr class="table-filters">
          <td><input name="A-process_id" class="form-filter"></td>
          <td><input name="A-name" class="form-filter"></td>
          <td><input name="A-description" class="form-filter"></td>
          <td><input name="A-num_stages" class="form-filter"></td>
          <td><input name="creator" class="form-filter"></td>
          <td class="table-actions">
            <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
            <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
          </td>
      </tr>
			  </thead>
			  <tbody>
			  </tbody>
			  </table>
			</div>
      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->
<script type="text/javascript">
  var deleteObj = new handleData({ controller : 'manage_workflow', method : 'delete_workflow', module: '<?php echo PROJECT_CORE ?>'});
</script>