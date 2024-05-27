<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
    
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s8 m8 l8">
            <ol class="breadcrumb m-n p-b-sm">
             <?php get_breadcrumbs();?>
          </ol>
          <h5 class="breadcrumbs-title">Deductions</h5>
          </div>
          <div class="col s4 p-t-lg right-align">
		  <div class="btn-group">
		    <button class="" type="button" onclick="window.location.reload()"><i class="flaticon-arrows97"></i> Refresh</button>
		  </div>
		  
		  <div class="input-field inline p-l-md">
		    <button class="btn btn-success waves-effect waves-light md-trigger" data-modal="modal_add_employee_to_deduction" onclick="modal_add_employee_to_deduction_init()">Add Employees</a></button>
		  </div>
	  </div>
        </div>
      </div>
    </div>
    <!--breadcrumbs end-->
    
    <!--start container-->
    <div class="container">
      <div class="section panel p-lg">
        <label>Filtered by: </label>
      </div>

      <div class="section panel p-lg">
      <!--start section-->
      <div class="pre-datatable filter-left"></div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_deduction_type_employee">
          <thead>
            <tr>
              <th width="20%">Personnel Number</th>
              <th width="20%">Personnel Name</th>
              <th width="20%">Date Started</th>
              <th width="20%">Status</th>
              <th width="20%">Actions</th>
            </tr>
            <!-- For Advanced Filters -->
            <tr class="table-filters">
              <td><input name="employee_no" class="form-filter"></td>
              <td><input name="employee_name" class="form-filter"></td>
              <td><input name="date_started" class="form-filter"></td>
              <td><input name="status" class="form-filter"></td>
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

