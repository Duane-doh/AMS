<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n">        
  <!--breadcrumbs start-->
  	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
	  <div class="container">
	    <div class="row">
	      <div class="col s12 m12 l12">
          <h5 class="breadcrumbs-title">Leaves</h5>
	        <ol class="breadcrumb m-n">
	             <?php get_breadcrumbs();?>
	        </ol>
	      </div>
	    </div>
	  </div>
	</div>
<!--breadcrumbs end-->

<!--start container-->
  <div class="container">
    <div class="section panel p-sm p-t-n">
  	<!--start section-->
  		<div class="pre-datatable filter-left"></div>
  		 <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_leave_type_list">
          <thead>
          <tr>
            <th width="70%">Leave type name</th>
            <th width="20%">Status</th>    
             <th width="10%">Action</th>
          </tr>
          <tr class="table-filters">
          
            <td><input name="leave_type_name" class="form-filter"></td>
            <td><input name="active_flag" class="form-filter"></td>
            <td class="table-actions">
              <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
              <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
            </td>
          </tr>
          </thead>
            <tbody>
            </tbody>
        </table>
      </div>
            
    </div>
  </div>
        <!--end container-->
</section>