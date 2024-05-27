<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <ol class="breadcrumb m-n p-b-sm">
                    <li><a href="index.html">Human Resources</a></li>
                    <li><a class="active"><?php echo SUB_MENU_PERFORMANCE_EVALUATION; ?></a></li>
                </ol>
                <h5 class="breadcrumbs-title"><?php echo SUB_MENU_PERFORMANCE_EVALUATION; ?></h5>
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
        			  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_details">
          			  <thead>
          				<tr>
          				  <th width="20%">Employee #</th>
          				  <th width="20%">First Name</th>
          				  <th width="20%">Last Name</th>
          				  <th width="20%">Status</th>
          				  <th width="10%">Actions</th>
          				</tr>
                 <tr class="table-filters">
                    <td><input name="employee_id" class="form-filter"></td>
                    <td><input name="first_name" class="form-filter"></td>
                    <td><input name="last_name" class="form-filter"></td>
                    <td><input name="active_flag" class="form-filter"></td>
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
