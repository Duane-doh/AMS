<?php $data_id = 'modal_pds_upload/' . ACTION_ADD; ?>
<input type="hidden" name="action_id" value="<?php echo $action_id ;?>">

<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
     <!--breadcrumbs start-->
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
      <div class="row">
        <div class="col s7 m7 l7">
          <h5 class="breadcrumbs-title"><?php echo SUB_MENU_DEDUCTIONS; ?></h5>
          <ol class="breadcrumb m-n p-b-sm">
              <?php get_breadcrumbs();?>
          </ol>
        </div>
          <div class="col l5 m5 s5 p-t-n user-avatar">
            <div class="row m-n">
              <img class="circle" width="65" height="65" src="<?php echo base_url().PATH_IMAGES. 'avatar/avatar_001.jpg'?>"/> 
                <label class="dark font-xl"><?php echo ucwords($personal_info['fullname']); ?></label><br>
                <label class="font-lg"><?php echo $personal_info['position_name']; ?></label><br>
                <label class="font-md"><?php echo $personal_info['name']; ?></label>
            </div>
          </div>
      </div>
    </div>
    <!--breadcrumbs end-->

    <!--start container-->
    <div class="container p-t-n">
      <div class="section panel p-lg p-t-sm">
  <!--start section-->
        <div id="article_tab">
          <div class="row m-t-md">
            <ul class="collection col l3 s12 m-n m-r-md p-n b-n m-b-md">
              <div class="collection m-n b-n pds-nav">
                <a class="collection-item active" href="<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/statutory_details/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>"><span class="hide-display show-on-hover"><i class="material-icons left">account_balance</i> Statutory Deductions</span></a>
                <a class="collection-item" href="<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/other_deductions/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>"><span class="hide-display show-on-hover"><i class="material-icons left">remove_circle</i>Other Deductions</span></a>
                <?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
                <a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/request_certificate/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission; ?>"><i class="flaticon-add175"></i> <span class="hide-display show-on-hover m-l-sm">Request Certificate</span></a>
                <?php endif; ?>
                <a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/deduction_history/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission; ?>"><i class="flaticon-list82"></i> <span class="hide-display show-on-hover m-l-sm">Deduction History</span></a>
              </div>  
            </ul>
            <div id="tab_content" class="panel col l9 s8 m-r-n-md m-n p-n">
              
            </div>
          </div>
        </div>
   <!--end section-->              
      </div>
    </div>
    <!--end container-->
  </div>
</section>
<!-- END CONTENT -->
  
<script type="text/javascript">
$(function (){

  $('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/statutory_details/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>')
  $('.collection > a').click(function(event){

    event.preventDefault();

    $('#tab_content').isLoading();
    var href = $(this).attr('href');
    $.get(href, function(result){
      if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker').length)
        $('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');

      $('#tab_content').html(result);
      $('#tab_content').isLoading( "hide" );
    });

        $('.collection > a').removeClass('active');
        $(this).addClass('active');
  });
})
</script>