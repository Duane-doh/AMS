

<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class="grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s6 m6 l6">
					<h5 class="breadcrumbs-title">General Payroll</h5>
					<ol class="breadcrumb m-n">
						<?php get_breadcrumbs(); ?>
					</ol>
				</div>
			</div>
		</div>
		
		<!--start container-->
		<div class="container p-t-n">
			<div class="section panel p-lg p-t-n">
				<div class="col l6 m6 s3 right-align m-b-n-lg">
					<div class="input-field inline p-l-md">
						<?php if ($permission_add) {?>
						<button class="btn btn-success" onclick="content_form('<?php echo "payroll/prepare_payroll/".ACTION_ADD."/$module', '".PROJECT_MAIN?>')">Prepare Payroll</button>
						<?php } else {?>
						<span>&nbsp;</span>
						<?php }?>
					</div>
				</div>
				<div class="pre-datatable filter-left"></div>
				<div class="p-t-xl">
					<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="payroll_list">
						<thead>
							<tr>
								<th width="10%">Payroll Type</th>
								<th width="10%">Bank Name</th>
								<th width="10%">Date From</th>
								<th width="10%">Date To</th>
								<th width="10%">Status</th>
								<!-- marvin : include remarks for batching : start -->
								<th width="10%">Remarks</th>
								<!-- marvin : include remarks for batching : end -->
								<th width="10%">Action</th>
							</tr>
							<tr class="table-filters">
								<td><input name="c-payroll_type_name" class="form-filter"></td>
								<td><input name="d-bank_name" class="form-filter"></td>
								<td><input name="b-date_from" class="form-filter"></td>
								<td><input name="b-date_to" class="form-filter"></td>
								<td><input name="e-payout_status_name" class="form-filter"></td>
								<!-- marvin : include remarks for batching : start -->
								<td><input name="b-remarks" class="form-filter"></td>
								<!-- marvin : include remarks for batching : end -->
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
			</div>
		</div>
		<!--end container-->		
		
	</div>
	<!--breadcrumbs end-->
</section>
<!--end section-->   
<!-- END CONTENT -->

