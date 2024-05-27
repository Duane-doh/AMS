
    <section id="content" class="p-t-n m-t-n ">        
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s6 m6 l6">
                <h5 class="breadcrumbs-title"> Requests </h5>
                <ol class="breadcrumb m-n p-b-sm">
                    <?php get_breadcrumbs();?>
                </ol>
<!--                 <span>Manage all types of requests (leaves,certification and personal record changes).</span>
 -->              </div>
              <div class="col s6 right-align ">
                <div class="input-field inline none">
                  <?php 
                    $salt     = gen_salt();
                    $module     = MODULE_USER;
                    $token_add    = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
                    $url_add    = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module;
                  ?>
                <button class="btn btn-success md-trigger" data-modal="modal_employee_request" onclick="modal_employee_request_init('<?php echo $url_add?>')">Create New Request</a></button>
                </div>
               </div>

            </div>
          </div>
        </div>
        
        <!--start container-->
        <div class="container p-t-n">
          <div class="section panel p-lg p-t-sm">
      <!--start section-->
      <div class="pre-datatable filter-left"></div>
      <div class="p-t-lg">
          <div class="panel">
            <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_request">
            <thead>
            <tr>
              <th width="15%">Request No</th>
              <th width="20%">Request Type</th>
              <th width="20%">Date Requested</th>
              <th width="20%">Requested Date/s For Manual Adjustment/Filed Leave</th>
              <th width="15%">Status</th>
              <th width="10%">Actions</th>
            </tr>
            <tr class="table-filters">
            <td><input name="A-request_code" class="form-filter"></td>
            <td><input name="B-request_type_name" class="form-filter"></td>
            <td><input name="DATE_FORMAT(A-date_requested,'%M %d, %Y')" class="form-filter"></td>
            <td><input name="" class="form-filter" disabled></td>
            <td><input name="C-request_status_name" class="form-filter"></td>
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
      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->
<script>
  function cancel_request(action, id, token, salt, module){
  
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
         $.post($base_url + "main/employee_requests/cancel_request",data, function(result) {
              if(result.status){

                notification_msg("<?php echo SUCCESS ?>", result.message);
                load_datatable('table_employee_request', '<?php echo PROJECT_MAIN ?>/employee_requests/get_employee_requests',false,0,0,true);
           
              } 
              else {
                notification_msg("<?php echo ERROR ?>", result.message);
              }
           }, 'json');
    },
    onCancelBut : function() {},
    onLoad : function() {
      $('.confirmModal_content h4').html('Are you sure you want to cancel this request?'); 
      $('.confirmModal_content p').html('This action will cancel this request and cannot be undone.');
    },
    onClose : function() {}
  });
}
</script>
