
<section id="content" class="p-t-n m-t-n ">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s6 m6 l6">
					<h5 class="breadcrumbs-title"><?php echo SUB_MENU_SPECIAL_PAYROLL; ?></h5>
					<ol class="breadcrumb m-n p-b-sm">
						<?php get_breadcrumbs(); ?>
					</ol>
				</div>
			</div>
		</div>
		
		<!--start container-->
		<div class="container p-t-n">
			<div class="section panel p-lg p-t-sm">
				<div class="col l6 m6 s3 right-align m-b-n-lg">
	                <div class="input-field inline p-l-md z-input">
						<?php if ($permission_add) {?>                
	                    <button class="btn btn-success" onclick="content_form('<?php echo "special_payroll/prepare_special_payroll/".ACTION_ADD."', '".PROJECT_MAIN?>')">Prepare Special Payroll</button>
						<?php } else {?>
						<span>&nbsp;</span>
						<?php }?>
	                </div>
	            </div>
				<!--start section-->
				<div class="pre-datatable filter-left"></div>
				<div class="p-t-xl">
					<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="payroll_list">
						<thead>
							<tr>
								<th width="10%">Compensation Type</th>
								<th width="10%">Payout Date</th>
								<th width="10%">Status</th>
								<th width="10%">Action</th>
							</tr>
							<tr class="table-filters">
								<td><input name="D-compensation_name" class="form-filter"></td>
								<td><input name="E-effective_date" class="form-filter"></td>
								<td><input name="F-payout_status_name" class="form-filter"></td>
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
<!-- END CONTENT -->
