<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
<div id="breadcrumbs-wrapper" class=" grey lighten-3">
	<div class="container">
		<div class="row">
			<div class="col s82 m8 l8">				
				<h5 class="breadcrumbs-title"><?php echo SUB_MENU_ATTENDANCE_PERIOD; ?></h5>
				<ol class="breadcrumb m-n p-b-sm">
					 <?php get_breadcrumbs();?>
				</ol>
			</div>
		</div>
	</div>
</div>
<div class="container" id="page_content">
	<div class="section panel p-lg p-t-n">
		<div class="col l6 m6 s6 right-align m-b-n-lg">
			
				<div class="input-field inline p-l-md z-input">
						<?php 
						if($this->permission->check_permission(MODULE_TA_ATTENDANCE_PERIOD, ACTION_ADD)):
						$salt      = gen_salt();
						$token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . MODULE_TA_ATTENDANCE_PERIOD, $salt);
						$url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".MODULE_TA_ATTENDANCE_PERIOD;
					?>

					<button class="btn btn-success btn-success  md-trigger green" data-modal="modal_attendance_period" onclick="modal_attendance_period_init('<?php echo $url_add; ?>')">Add <?php echo SUB_MENU_ATTENDANCE_PERIOD; ?></button>
				<?php endif;?>
				</div>
			</div>
			<div class="pre-datatable filter-left"></div>
				<div class="p-t-xl">
					<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_attendance_periods">
						<thead>
						<tr>
							<th width="18%">Payroll Type</th>
							<th width="18%">Period From</th>
							<th width="18%">Period To</th>
							<th width="17%">Status</th>
							<!-- marvin : include remarks for batching : start -->
							<th>Remarks</th>
							<!-- marvin : include remarks for batching : end -->
							<th width="12%">Actions</th>
						</tr>
						<tr class="table-filters">
							<td><input name="B-payroll_type_name" class="form-filter"></td>
							<td><input name="period_from" class="form-filter"></td>
							<td><input name="period_to" class="form-filter"></td>
							<td><input name="C-period_status_name" class="form-filter"></td>
							<!-- marvin : include remarks for batching : start -->
							<td><input name="A-remarks" class="form-filter"></td>
							<!-- marvin : include remarks for batching : end -->
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
</section>

<script>

  function process_period(action, id, token, salt, module){
  
  $('#confirm_modal').confirmModal({
    topOffset : 0,
    onOkBut : function() {
      var data = {
                'action': action,
                'id'    : id,
                'token' : token,
                'salt'  : salt,
                'module': module
            };
            $('#page_content').isLoading();
         $.post($base_url + "main/attendance_period/process_period_update",data, function(result) {
              if(result.status){

                notification_msg("<?php echo SUCCESS ?>", result.message);
                load_datatable('table_attendance_periods', '<?php echo PROJECT_MAIN ?>/attendance_period/get_attendance_period_list',false,0,0,true);
           
              } 
              else {
                notification_msg("<?php echo ERROR ?>", result.message);
              }
              $('#page_content').isLoading('hide');
           }, 'json');
    },
    onCancelBut : function() {},
    onLoad : function() {
      $('.confirmModal_content h4').html('Proceed process?'); 
      $('.confirmModal_content p').html('Processcing this attendance period will take some time and cannot be undone.');
    },
    onClose : function() {
    	 
    }
  });
}
</script>