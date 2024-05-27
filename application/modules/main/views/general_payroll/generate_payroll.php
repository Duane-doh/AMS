<?php $data_id = 'modal_payroll_reports/' . ACTION_VIEW; ?>


<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
                <ol class="breadcrumb m-n p-b-sm">
                    <li><a href="index.html">Payroll</a></li>
                    <li><a href="index.html">General Payroll</a></li>
                    <li><a class="active">Payroll Reports</a></li>
                </ol>
              </div>
            </div>
            <div class="row m-b-n page-title p-t-sm">
				<div class="col s6 p-r-n">
					<h5>
						<?php echo PAYROLL_REPORTS; ?>
						<span>Payroll Periond: <b>May 1, 2016 - May 15, 2016</b></span>
						<span>Type of Employemt: <b> Regular</b></span>
						<span>Payroll Status: <b> Draft</b></span>
					</h5>
				</div>
			  </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        
        
        <!--start container-->
        <div class="container">
          <div class="section panel p-lg">
      <!--start section-->
		<!-- START FILTER -->
		<div class="row blue-grey lighten-5 m-b-lg box-shadow">
		  <div class="table-display">
			<div class="table-cell p-md b-r valign-top" style="width:15%; border-style:dashed!important; border-right-color:#d2d2d2!important;">
			  <h6>Filter by criteria</h6>
			  <small>Use filters to limit results</small>
			</div>
			<div class="table-cell p-md b-r valign-top" style="width:15%; border-style:dashed!important; border-right-color:#d2d2d2!important;">
			  <label for="employee_no" class="active">Employee No</label>
			  <input type="text" name="employee_no" id="employee_no">
		    </div>
		    <div class="table-cell p-md b-r valign-top" style="width:15%; border-style:dashed!important; border-right-color:#d2d2d2!important;">
			  <label for="last_name" class="active">Last Name</label>
			  <input type="text" name="last_name" id="last_name">
		    </div>
			<div class="table-cell p-md b-r valign-top" style="width:15%; border-style:dashed!important; border-right-color:#d2d2d2!important;">
			  <label for="first_name" class="active">First Name</label>
			  <input type="text" name="first_name" id="first_name">
		    </div>
		    <div class="table-cell p-md b-r valign-top" style="width:10%; border-style:dashed!important; border-right-color:#d2d2d2!important;">
			  <label for="Office" class="active">Office</label>
			  <input type="text" name="Office" id="Office">
			  </select>
		    </div>
		    <div class="table-cell p-md b-r valign-top" style="width:10%; border-style:dashed!important; border-right-color:#d2d2d2!important;">
			  <label for="Status" class="active block m-b-sm">Status</label>
			  <select class="selectize filter" id="Status">
			  <option value="">Filtered value</option>
			  </select>
		    </div>
			<div class="table-cell valign-middle p-md center-align" style="width:20%">
			  			<a class="waves-effect grey darken-2 btn p-l-sm  p-r-sm"><i class="material-icons left m-r-xs">restore</i>Reset</a>
			  			<a class=" btn p-l-sm  p-r-sm"><i class="material-icons left m-r-xs">search</i>Search</a>
		    </div>
		  </div>
		</div>
		<!-- END FILTER -->

		<!-- TABLE -->
		<div class="row m-t-lg">
			<div class="pre-datatable"></div>
				<div>
				<table cellpadding="0" cellspacing="0" class="table table-default table-layout-auto" id="payroll_report">
					 <thead>
						<tr>
						  <th width="15%" class="avatar_th">Employee #</th>
						  <th width="10%">Last Name</th>
						  <th width="10%">First Name</th>
						  <th width="15%">Office</th>
						  <th width="14%">Net Pay</th>
						  <th width="13%">Total Benefits</th>
						  <th width="13%">Total Deduction</th>
						  <th width="10%">Actions</th>
						</tr>
					 </thead>
					<tbody>
					  
					</tbody>
				</table>
			</div>
			<!-- START BODY -->
			<div class="panel m-t-sm bg-none m-t-lg">
				<div class="row">
					<div class="col s3">
					  <div class="panel-image box-shadow mute">
						<i class="flaticon-minus99 circle"></i>
						<small class="font-semibold m-b-md block">PREPARED BY</small>
						<div class="m-b-xs font-semibold truncate">____________________</div>
						<em class="truncate">HR Manager</em>
					  </div>
					</div>	
					
					<div class="col s3">
					  <div class="panel-image box-shadow mute">
						<i class="flaticon-minus99 circle"></i>
						<small class="font-semibold m-b-md block">CERTIFIED BY</small>
						<div class="m-b-xs font-semibold truncate">____________________</div>
						<em class="truncate">Chief, Personnel Administration Division</em>
					  </div>
					</div>	
					
					<div class="col s3">
					  <div class="panel-image box-shadow mute">
						<i class="flaticon-minus99 circle"></i>
						<small class="font-semibold m-b-md block">CERTIFIED BY</small>
						<div class="m-b-xs font-semibold truncate">____________________</div>
						<em class="truncate">Chief Accountant</em>
					  </div>
					</div>	
			  	</div>
			</div>
			<!-- END WORKFLOW -->

			<!-- START FLOATING BUTTON -->
			<div class="fixed-action-btn horizontal" style="bottom: 45px; right: 24px;">
			    <a class="btn-floating btn-large red">
			      <i class="large material-icons">mode_edit</i>
			    </a>
			    <ul>
			      <li><a class="btn-floating red tooltipped" data-tooltip="Submit Payroll"  id="prepare_payroll"><i class="material-icons">done_all</i></a></li>
			      <li><a class="btn-floating yellow darken-1 md-trigger tooltipped" data-tooltip="Payroll Reports" data-modal="modal_default" onclick="modal_init('<?php echo $data_id; ?>')"><i class="material-icons">settings</i></a></li>
			    </ul>
		  	</div>
		  	<!-- END FLOATING BUTTON -->
		</div>
      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->
<script type="text/javascript">
var modalObj = new handleModal({ controller : 'payroll', modal_id: 'modal_default', module: '<?php echo PROJECT_MAIN ?>' });
$(function (){
	$("#prepare_payroll").on('click', function (){
		window.location = $base_url + 'main/payroll/';
	});
})
</script>