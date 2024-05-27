<form id="performance_evaluation_form">
		<div class="row">
			<div class="col s12">
				<div class="col s12 p-t-lg user-avatar">
					<div class="row m-n ">
						<?php 
							$avatar_src = base_url() . PATH_USER_UPLOADS . $avatar['photo'];
							$avatar_src = @getimagesize($avatar_src) ? $avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
						?>
						<img class="circle" width="65" height="65" src="<?php echo $avatar_src?>"/> 
                        <label class="dark font-xl"><?php echo ucwords($personal_info['fullname']); ?></label><br>
                        <label class="font-lg"><?php echo $personal_info['position_name']; ?></label><br>
                        <label class="font-md"><?php echo $personal_info['name']; ?></label><br>
                        <label class="font-md" style="padding-left: 75px;"><?php echo $personal_info['agency_employee_id']; ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<div id="article_tab">
			          <div class="row m-t-md">
			            <ul class="collection col s12 m-n m-r-md p-n b-n">
			              <div class="collection m-n b-n">
			                <a class="collection-item active col s4 p-sm" href="<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/statutory_details/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>"><span class="hide-display show-on-hover"><i class="material-icons left">account_balance</i> Statutory Deductions</span></a>
			                <a class="collection-item col s4 p-sm" href="<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/other_deductions/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>"><span class="hide-display show-on-hover"><i class="material-icons left">remove_circle</i>Other Deductions</span></a>
			              </div>  
			            </ul>
			            <div id="deduction_content" class="panel col s12 m-r-n-md m-n p-n m-t-n-xl p-t-sm">
			              
			            </div>
			          </div>
			        </div>
			</div>
		</div>
	<div class="md-footer default">
	</div>
</form>
 
<script type="text/javascript">
$(function (){

  $('#deduction_content').load('<?php echo base_url() . PROJECT_MAIN ."/deductions/get_tab_employee_deductions/statutory_details/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission;?>')
  $('.collection > a').click(function(event){

    event.preventDefault();

    $('#deduction_content').isLoading();
    var href = $(this).attr('href');
    $.get(href, function(result){
      if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker').length)
        $('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');

      $('#deduction_content').html(result);
      $('#deduction_content').isLoading( "hide" );
    });

        $('.collection > a').removeClass('active');
        $(this).addClass('active');
  });
})
</script>