
<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s8 m8 l8">
                <h5 class="breadcrumbs-title">Roles</h5>
                <ol class="breadcrumb m-n p-b-sm">
                   <?php get_breadcrumbs();?>
                </ol>
              </div>
              <div class="col s4 p-t-lg right-align">
				  <div class="btn-group">
				    <button type="button" onclick="window.location.reload()"><i class="flaticon-arrows97"></i> Refresh</button>
				  </div>
				  
				  <div class="input-field inline p-l-md">
					<button type="button" class="btn  md-trigger btn-success" data-modal="modal_roles" id="add_role" name="add_role" onclick="modal_init()">Add New Role</button>
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

      		<div class="m-t-lg">
			<div class="pre-datatable filter-left"></div>
			<div>
			  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="role_table">
			  <thead>
				<tr>
				  <th width="20%">Code</th>
				  <th width="28%">Name</th>
				  <th width="10%">Built In</th>
				  <th width="30%">Assigned System/s</th>
				  <th width="12%" class="text-center">Actions</th>
				</tr>
					<tr class="table-filters">
							<td><input name="a-role_code" class="form-filter"></td>
							<td><input name="a-role_name" class="form-filter"></td>
							<td><input name="built_in_flag" class="form-filter"></td>
							<td><input name="c-system_name" class="form-filter"></td>
							<td class="table-actions">
								<a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
								<a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
							</td>
						</tr>
			  </thead>
			  </table>
			</div>
		</div>

      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->

<!-- Modal -->
<div id="modal_roles" class="md-modal md-effect-<?php echo MODAL_EFFECT ?>">
  <div class="md-content">
	<a class="md-close icon">&times;</a>
	<h3 class="md-header">Role</h3>
	<div id="modal_roles_content"></div>
  </div>
</div>
<div class="md-overlay"></div>


<script type="text/javascript">
var modalObj = new handleModal({ controller : 'roles', modal_id: 'modal_roles', module: '<?php echo PROJECT_CORE ?>' });
	deleteObj = new handleData({ controller : 'roles', method : 'delete_role', module: '<?php echo PROJECT_CORE ?>' });
	updateObj = new handleData({ controller : 'roles', method : 'process' });
	
$(function(){	
	$("#cancel_role").on("click", function(){
		modalObj.closeModal();
	});
});
</script>