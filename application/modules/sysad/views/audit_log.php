<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s6 m6 l6">
                <h5 class="breadcrumbs-title">Audit Trail</h5>
                <span>Keep track of all activities in your system.</span>
                <ol class="breadcrumb m-n p-b-sm">
                     <?php get_breadcrumbs();?>

                </ol>
              </div>
              <div class="col s6 right-align">
               <div class="row m-b-n">
                 <div class="col s7 p-t-md">
                  <div class="btn-group">
                   <button type="button" onclick="window.location.reload()"><i class="flaticon-arrows97"></i> Refresh</button>
                   <button type="button" class="dropdown-button"  data-beloworigin="false" data-activates="dropdown-download-to"><i class="flaticon-inbox36"></i> Download</button>
                 </div>

                 <!-- Print To Dropdown Structure -->
                 <div id="dropdown-download-to" class="dropdown-content">
                   <ul class="collection actions">
                     <li class="collection-item"><a href="#!" class="collection-link pdf" onclick="$('#audit_log_table').tableExport({type:'pdf',escape:'false',ignoreColumn: [4], pdfFontSize:'7'})">PDF</a></li>
                     <li class="collection-item"><a href="#!" class="collection-link xlsx" onclick="$('#audit_log_table').tableExport({type:'excel',escape:'false',ignoreColumn: [4]})">Excel</a></li>
                     <li class="collection-item"><a href="#!" class="collection-link docx" onclick="$('#audit_log_table').tableExport({type:'doc',escape:'false',ignoreColumn: [4]})">Word&nbsp;Document</a></li>
                   </ul>
                 </div>
                 <!-- End Print To Dropdown Structure -->
               </div>

               <div class="col s5 left-align p-t-md">
                <label>Filter by System</label>
                <select name="filter_audit_log" id="filter_audit_log" class="selectize" placeholder="Select System">
                  <option value=""></option>
                  <option value="0">All</option>
                  <?php foreach($systems as $system): ?>
                   <option value="<?php echo $system['system_code'] ?>"><?php echo $system['system_name'] ?></option>
                 <?php endforeach; ?>
               </select>
             </div>
            </div>
            </div>
            </div>
          </div>
        </div>
        <!--breadcrumbs end-->
        <!--start container-->
        <div class="container">
          <div class="section panel p-lg">
      <!--start section-->
            <div class="m-t-lg">
              <div class="pre-datatable filter-left"></div>
              <div>
                <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="audit_log_table">
                  <thead>
                   <tr>
                      <th width="25%">User</th>
                      <th width="12%">Module</th>
                      <th width="30%">Activity</th>
                      <th width="10%">Date</th>
                      <th width="15%">I.P</th>
                      <th width="8%" class="text-center">Actions</th>
                   </tr>
                   <tr class="table-filters">
                      <td><input name="fullname" class="form-filter"></td>
                      <td><input name="C-module_name" class="form-filter"></td>
                      <td><input name="A-activity" class="form-filter"></td>
                      <td><input name="A-activity_date" class="form-filter"></td>
                      <td><input name="A-ip_address" class="form-filter"></td>
                      <td class="table-actions">
                        <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Filter" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
                        <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
                      </td>
                    </tr>
                 </thead>
               </table>
             </div>
            </div>
      <!--end section-->              
          </div>
        </div>
        <!--end container-->

    </section>
<!-- END CONTENT -->

<!-- Modal -->
<div id="modal_roles" class="md-modal md-effect-<?php echo MODAL_EFFECT ?>">
  <div class="md-content">
   <a class="md-close icon">&times;</a>
   <h3 class="md-header">Roles</h3>
   <div id="modal_roles_content"></div>
   <div class="md-footer default">
     <?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
     <button class="btn " id="save_role" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
     <?php //endif; ?>
     <a class="waves-effect waves-teal btn-flat" id="cancel_role">Cancel</a>
   </div>
 </div>
</div>

<div class="md-modal md-effect-<?php echo MODAL_EFFECT ?> lg md-default" id="modal_audit_log">
  <div class="md-content">
   <a class="md-close icon">&times;</a>
   <h3 class="md-header">Audit Trail Details</h3>
   <div id="modal_audit_log_content"></div>
 </div>
</div>
<div class="md-overlay"></div>

<script type="text/javascript">
var modalObj = new handleModal({ controller : 'audit_log', modal_id: 'modal_audit_log', module: '<?php echo PROJECT_CORE ?>' });

$(function(){ 
 $("#close_audit").on("click", function(){
  modalObj.closeModal();
});

 <?php if(ISSET($system_code)){ ?>
   $("#filter_audit_log").val("<?php echo $system_code ?>");
   <?php }else{ ?>
     $("#filter_audit_log").val("0");
     <?php } ?>

     $("#filter_audit_log").change(function(){
      var system_code = $(this).val();

      if(system_code != 0){
       window.location.href = "<?php echo base_url() . PROJECT_CORE ?>/audit_log/filter/" + system_code;
     }else{
       window.location.href = "<?php echo base_url() . PROJECT_CORE ?>/audit_log/";
     }
   });
   });
 </script>
