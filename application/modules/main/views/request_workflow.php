
 <section id="content" class="p-t-n m-t-n ">
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12">
              	<h5 class="breadcrumbs-title">Request Details</h5>
                <ol class="breadcrumb m-n p-b-sm">
                	<?php get_breadcrumbs();?>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="container">
          <div class="section">
      			<div class="row m-n">
      				<input type="hidden" id="request_id" value="<?php echo $id ?>"/>
					<input type="hidden" id="request_action" value="<?php echo $action ?>"/>
					<input type="hidden" id="request_module" value="<?php echo $module ?>"/>
					<div class="panel p-sm col s3 m-l-n-md m-r-xs">
						<div class="row m-n m-t-sm">
							<div class="row m-n">
								<div class="col s2 p-l-n">
									<?php 
			                            /* GET USER AVATAR */
			                            $emp_avatar_src = base_url() . PATH_USER_UPLOADS . $request_info['photo'];
			                            $emp_avatar_src = @getimagesize($emp_avatar_src) ? $emp_avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
			                          ?>
			                        <img class="circle" width="65" height="65" src="<?php echo  $emp_avatar_src?>"/> 
								</div>
								<div class="col s10 p-l-lg p-t-sm p-n">
									<div class="row m-n">
										<div class="col s12">
											<label class="dark font-lg">
												<?php //echo isset($request_info['first_name']) ? ucfirst($request_info['first_name']):""?> <?php //echo isset($request_info['last_name']) ? ucfirst($request_info['last_name']):""?>
												
												<?php 
													  // ====================== jendaigo : start : change name format ============= //
													  echo isset($request_info['first_name']) ? ucfirst($request_info['first_name']):"";
													  echo (isset($request_info['middle_name']) AND ($request_info['middle_name']!='NA' OR $request_info['middle_name']!='N/A' OR $request_info['middle_name']!='-' OR $request_info['middle_name']!='/')) ? " ".ucfirst(substr($request_info['middle_name'], 0, 1))."." : "";
													  echo isset($request_info['last_name']) ? " ".ucfirst($request_info['last_name']):"";
													  echo isset($request_info['ext_name']) ? " ".ucfirst($request_info['ext_name']):"";
													  // ====================== jendaigo : end : change name format ============= //
												?>
											</label>
										</div>
									</div>
									<div class="row m-n p-t-sm">
										<div class="col s12">
											<label class="font-md"><?php echo isset($request_info['agency_employee_id']) ? $request_info['agency_employee_id']:""?></label>
										</div>
									</div>
									<div class="row m-n none">
										<div class="col s12">
											<label class="font-sm">Bureau of Quarantine</label>
										</div>
									</div>
								</div>
							</div>
							<hr>
						</div>
						<div class="row m-n m-t-md">
							<div class="col s12">
								<div class="row">
									<div class="col s6">
										<h6 class="dark"><label>Request Number</label></h6>
									</div>
									<div class="col s6">
										<h6><label class="dark"><?php echo isset($request_info['request_code']) ? $request_info['request_code']:""?></label></h6>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<h6 class="dark"><label>Type</label></h6>
									</div>
									<div class="col s6">
										<h6><label class="dark"><?php echo isset($request_info['request_type_name']) ? $request_info['request_type_name']:""?></label></h6>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<h6 class="dark"><label>Date Requested</label></h6>
									</div>
									<div class="col s6">
										<h6><label class="dark"><?php echo isset($request_info['date_requested']) ? $request_info['date_requested']:""?></label></h6>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<h6 class="dark"><label>Date Processed</label></h6>
									</div>
									<div class="col s6">
										<h6><label class="dark"><?php echo isset($request_info['date_processed']) ? $request_info['date_processed']:"--:--"?></label></h6>
									</div>
								</div>
								<?php 
								if(isset($request_info['requested_date']))
								{
									$req_date = "
									<div class='row'>
									<div class='col s6'>
									";
									if($request_info['request_type_id'] == REQUEST_LEAVE_APPLICATION)
									{
										$req_date .= "<h6 class='dark'><label>Leave Duration</label></h6>";
									}elseif($request_info['request_type_id'] == REQUEST_MANUAL_ADJUSTMENT)
									{
										$req_date .= "<h6 class='dark'><label>Attendance Date</label></h6>";
									}
									$req_date .= "
									</div>
									<div class='col s6'>
									<h6><label class='dark'> 
									". $request_info['requested_date']
									."
									</label></h6>
									</div>
								</div>
									";
									echo $req_date;
								}


								?>
								<div class="row">
									<div class="col s6">
										<h6 class="dark"><label>Request Status</label></h6>
									</div>
									<div class="col s6">
										<h6><label class="dark"><?php echo isset($request_info['request_status_name']) ? $request_info['request_status_name']:""?></label></h6>
									</div>
								</div>
								
								<!-- <div class="row">
									<div class="col s12 p-t-md p-b-md center">
										<a id="request_details" href='javascript:;' class='btn btn-success md-trigger m-l-n-md' data-modal='modal_request_changes' data-position='bottom' data-delay='50' onclick="modal_request_changes_init('<?php echo isset($url_security) ? $url_security :''?>')"><i class="flaticon-right198 white-text"> </i> <span class="m-l-xs white-text">Request Details</span></a>
									</div>
								</div> -->
							</div>
						</div>
					</div>
					<div class="col s9 panel p-md">
						<!-- <div class="col s12" style="margin-top: -112px;">
							<a id="supporting_documents" href='javascript:;' class='btn btn-success md-trigger pull-right' data-modal='modal_supporting_documents' data-position='bottom' data-delay='50' onclick="modal_supporting_documents_init('<?php echo isset($url_security) ? $url_security :''?>')"><i class="flaticon-text145 white-text"> </i> <span class="m-l-xs white-text">Supporting Documents</span></a>
						</div> -->
					<div class="pre-datatable filter-left"></div>
					<diV>
					  <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_request_workflow">
					  <thead>
						<tr>
						  <th width="20%">Stage</th>
						  <th width="20%">Assigned To</th>
						  <th width="15%">Date Assigned</th>
						  <th width="15%">Date Processed</th>
						  <th width="15%">Status</th>
						  <th width="10%">Actions</th>
						</tr>
						<!-- davcorrea : Disabled to optimize loading speed : START-->
			            <!-- <tr class="table-filters">
			              <td><input name="A-task_detail" class="form-filter"></td>
			              <td><input name="full_name" class="form-filter"></td>
			              <td><input name="assigned_date" class="form-filter"></td>
			              <td><input name="processed_date" class="form-filter"></td>
			              <td><input name="B-task_status_name" class="form-filter"></td>
			              <td class="table-actions">
			                <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
			                <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
			              </td> -->
						  <!-- davcorrea : END -->
			            </tr>
					  </thead>
						  <tbody>
						  
						  </tbody>
					  </table>
					</diV>
					<div class="row">
					  <div class="col s12">
					  <center>
						  <p><b class="red-text">Note:</b> Please coordinate with the Personnel Administration Division if the employee assigned for your approval is no longer under your jurisdiction.</p>
					  </center>
					  </div>
				  </div>
					</div>
					</div>       
          </div>
        </div>
    </section>
<!-- END CONTENT -->
<script>
function get_task(task_data){
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			
		    var option = {
					url  :  $base_url + 'main/requests/get_task/' + task_data,
					data : null,
					success : function(result){
						if(result.status)
						{
							var data_post = {
									'request_id'	: $('#request_id').val(),
									'request_module': $('#request_module').val(),
									'request_action': $('#request_action').val()
								};
							notification_msg("<?php echo SUCCESS ?>", result.message);	
							load_datatable('table_request_workflow', '<?php echo PROJECT_MAIN ?>/requests/get_request_tasks',false,0,0,true,data_post);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						// button_loader('approve', 0);
					}
			};

			General.ajax(option);    
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Are you sure that you want to get this task?');	
			$('.confirmModal_content p').html('This task will be assigned to you for processing.');
		},
		onClose : function() {}
	});
}
</script>