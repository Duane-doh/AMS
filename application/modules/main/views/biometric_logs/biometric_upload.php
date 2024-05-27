<?php $data_id = 'modal_dtr_upload/' . ACTION_ADD; ?>

<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class="grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s6 m6 l6">
                <h5 class="breadcrumbs-title"> Attendance Logs </h5>
                <ol class="breadcrumb m-n p-b-sm">
                   <?php get_breadcrumbs(); ?>
                </ol>
              </div>
			  <!-- davcorrea: START : Moved Upload button -->
				<div class="col l6 m6 s6 right-align m-b-n-lg">
					<div class="input-field inline p-l-md z-input">
						<button class="btn btn-success  md-trigger" data-modal="modal_dtr_upload" onclick="modal_dtr_upload_init('<?php echo $data_id; ?>')">Upload <?php echo SUB_MENU_BIO_LOGS_UPLOAD; ?></button>
					</div>
					<div class="input-field inline p-l-md z-input">
						<button class="btn btn-success  md-trigger" data-modal="modal_generate_attendance" onclick="modal_generate_attendance_init('<?php echo ACTION_ADD; ?>')">Generate <?php echo SUB_MENU_BIO_LOGS_UPLOAD; ?></button>
					</div>
				</div>
				 <!-- davcorrea: START : Moved Upload button -->
        	</div>
          </div>
        </div>
        <!--breadcrumbs end-->
        
        <!--start container-->
        <div class="container">
		<!-- <div class="col l6 m6 s6 right-align m-b-n-lg">
					<div class="input-field inline p-l-md z-input">
						<button class="btn btn-success  md-trigger" data-modal="modal_dtr_upload" onclick="modal_dtr_upload_init('<?php //echo $data_id; ?>')">Upload <?php// echo SUB_MENU_BIO_LOGS_UPLOAD; ?></button>
					</div>
				</div> -->
			<!-- davcorrea: START : Date range filter -->
          <div class="section panel p-lg p-t-n">
		  <div class="form-basic">
        <div class="row"> 
          <div class="col s7">
            <div class="input-field m-t-sm"></div>
          </div>                   
          <div class="col s2 p-r-n">
            <div class="input-field">
              <label for="fltr_dtr_start" class="active">Attendance Date From</label>
              <input type="text" class="datepicker_start" id="fltr_dtr_start" value="<?php echo $fltr_dtr_start?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_start')"/>
            </div>
          </div>               
          <div class="col s2 p-l-n">
            <div class="input-field">
              <label for="fltr_dtr_end" class="active">Attendance Date To</label>
              <input type="text" class="datepicker_end" id="fltr_dtr_end" value="<?php echo $fltr_dtr_end?>" onkeypress="format_identifications('<?php echo DATE_FORMAT ?>',this.value,event,'fltr_dtr_end')"/>
            </div>
          </div>
          <div class="col s1 p-n">
            <div class="input-field p-t-xs">
              <a href="javascript:;" onclick="filter_attendance()" class="btn p-l-sm p-r-xs"><i class="flaticon-search95 "></i></a>
            </div>
          </div>
			<!-- davcorrea: date range filter 10/17/2023 : END -->
          	<div class="pre-datatable filter-left"></div>
				<div class="p-t-xl">
				  	<table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="dtr_file_list">
					  	<thead>
							<tr>
							 	<th width="20%">Attendance Date</th>
							  	<th width="20%">Date Uploaded</th>
							  	<th width="20%">Status</th>
							  	<th width="10%">Action</th>
							</tr>
							<tr class="table-filters">
								<td><input name="attendance_date" class="form-filter"></td>
								<td><input name="date_uploaded" class="form-filter"></td>
								<td><input name="file_status_name" class="form-filter"></td>
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
      <!--end section-->   
<!-- END CONTENT -->
<script>
	function process_attendance_logs(security_data = null)
	{
		var data = {
			security_data : security_data
		};
		$('#confirm_modal').confirmModal({
			topOffset : 0,
			onOkBut : function() {
				$.post('<?php echo base_url() . "main/biometric_logs/process_biometric_logs" ?>', data, function(x) {
					var result = jQuery.parseJSON(x);
					if(result.status)
					{
						notification_msg("<?php echo SUCCESS ?>", result.msg);
						load_datatable('dtr_file_list', '<?php echo PROJECT_MAIN ?>/biometric_logs/get_dtr_file_list',false,0,0,true);

					}
					else
						notification_msg("<?php echo ERROR ?>", result);

				});
			},
			onCancelBut : function() {},
			onLoad : function() {
				$('.confirmModal_content h4').html('Process Biometric');	
				$('.confirmModal_content p').html('Are you sure you want to process this attendance logs.');
			},
			onClose : function() {}
		});
	}
  // davcorrea: 10/17/2023 : include date range filter : start
	function filter_attendance() {
    var date_from = $('#fltr_dtr_start').val();
    var date_to   = $('#fltr_dtr_end').val(); 
    
    if(date_from != "" && date_to != "")
    {
      window.location.href = "<?php echo base_url() . PROJECT_MAIN . '/biometric_logs/index/'; ?>" + encodeURIComponent(btoa(date_from)) + "/" + encodeURIComponent(btoa(date_to));
    }
  }
  // davcorrea : include date range filter : end
</script>
