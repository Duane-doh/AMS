
<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s8 m8 l8">
                <h5 class="breadcrumbs-title">User Management</h5>
                <ol class="breadcrumb m-n p-b-sm">
                    <?php get_breadcrumbs();?>
                </ol>
              </div>
              <div class="col s4 p-t-lg right-align">
          <div class="btn-group">
            <button class="" type="button" onclick="window.location.reload()"><i class="flaticon-arrows97"></i> Refresh</button>
          </div>
          
          <div class="input-field inline p-l-md">
            <button class="btn  btn-success" name="add_user" id="add_user" type="button" onclick="content_form('users/form', '<?php echo PROJECT_CORE ?>')">Add New User</button>
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
  <div class="pre-datatable filter-left"></div>
<div>
  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="user_table">
    <thead>
      <tr>
        <th width="20%" style="padding-left:55px!important;">Username</th>
        <th width="20%">First Name</th>
        <th width="20%">Last Name</th>
        <th width="19%">Email</th>
        <th width="7%">Roles</th>
        <th width="7%">Status</th>
        <th width="7%" class="center-align">Actions</th>
      </tr>
      <!-- For Advanced Filters -->
      <tr class="table-filters">
        <td><input name="A-username" class="form-filter"></td>
        <td><input name="A-fname" class="form-filter"></td>
        <td><input name="A-lname" class="form-filter"></td>
        <td><input name="A-email" class="form-filter"></td>
        <td>
			<!--roles-->
			<!-- ===================== jendaigo : start : include filtering of role_code ============= -->
			<input name="C-role_code" class="form-filter">
			<!-- ===================== jendaigo : start : include filtering of role_code ============= -->
		</td>
        <td><input name="B-status" class="form-filter"></td>
        <td class="table-actions">
          <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
          <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
        </td>
      </tr>
    </thead>
  </table>
</div>

      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->
<script type="text/javascript">
  var deleteObj = new handleData({ controller : 'users', method : 'delete_user', module: '<?php echo PROJECT_CORE ?>'});
</script>
