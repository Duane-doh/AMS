
<!-- START CONTENT -->
    <section id="content" class="p-t-n m-t-n ">
        <!--breadcrumbs start-->
        <div id="breadcrumbs-wrapper" class=" grey lighten-3">
          <div class="container">
            <div class="row">
              <div class="col s8 m8 l8">
                <ol class="breadcrumb m-n p-b-sm">
                    <li><a href="#">Employee Portal</a></li>
                    <li><a href="#"><?php echo SUB_MENU_CR; ?></a></li>
                    <li><a class="active"><?php echo SUB_MENU_CR_LIST; ?></a></li>
                </ol>
                <h5 class="breadcrumbs-title"><?php echo SUB_MENU_CR_LIST; ?></h5>
              </div>
              <div class="col l4 m4 s4 p-t-sm user-avatar">
              <div class="row m-n">
              <img class="circle" width="65" height="65" src="<?php echo base_url().PATH_IMAGES. 'avatar/avatar_001.jpg'?>"/> 
              <label class="dark font-xl"><?php echo $personal_info['name']; ?></label><br>
              <label class="font-lg"><?php echo $personal_info['agency_employee_id']; ?></label><br>
              <label class="font-md"><?php echo $personal_info['office_name']; ?></label>
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
        <div class="col l6 m6 s3 right-align">
          <div class="input-field inline p-l-md">
            <?php 
                $salt      = gen_salt();
                $token_add = in_salt(DEFAULT_ID . '/' . ACTION_ADD  . '/' . $module, $salt);
                $url_add   = ACTION_ADD."/".DEFAULT_ID ."/".$token_add."/".$salt."/".$module."/".$employee_id;
            ?>
            <?php  if($module != 13) {?>
           <button class="btn btn-success btn-success md-trigger m-r-sm" data-modal="modal_contract_record" onclick="modal_contract_record_init('<?php echo $url_add; ?>')"><i class="flaticon-add175"></i>Add Contract Record</button>
           <?php } ?>
          </div>
        </div>
       
      <div class="pre-datatable filter-left"></div>
      <div>
        <table cellpadding="0" cellspacing="0" class="table table-advanced table-layout-auto" id="table_employee_contract_record">
        <thead>
        <tr>
          <th width="15%">Contract Number</th>
          <th width="15%">Start Date</th>
          <th width="20%">End Date</th>
          <th width="15%">Position</th>
          <th width="5%">Action</th>
         
        </tr>
       <!--  <tr class="table-filters">
          <td><input name="A-contract_number" class="form-filter"></td>
          <td><input name="A-contract_start" class="form-filter"></td>
          <td><input name="A-contract_end" class="form-filter"></td>
          <td><input name="C-position" class="form-filter"></td>
          <td class="table-actions">
            <a href="javascript:;" class="tooltipped filter-submit" data-tooltip="Submit" data-position="top" data-delay="50"><i class="flaticon-filter19"></i></a>
            <a href="javascript:;" class="tooltipped filter-cancel" data-tooltip="Reset" data-position="top" data-delay="50"><i class="flaticon-circle100"></i></a>
          </td>
        </tr> -->
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


