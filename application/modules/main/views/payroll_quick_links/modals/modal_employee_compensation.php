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
			                <a class="collection-item p-l-n active col s4 p-sm" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_SALARY."/".$action."/".$employee_id."/".$token."/".$salt."/".$module;?>"><i class="flaticon-money128"></i> <span class="hide-display show-on-hover m-l-sm"> Salary</span></a>
							<a class="collection-item p-l-n col s4 p-sm" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_BENEFITS."/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission?>"><i class="flaticon-credit92"></i> <span class="hide-display show-on-hover m-l-sm"> Benefits</span></a>
						  </div>  
			            </ul>
			            <div id="tab_content" class="panel col s12 m-r-n-md m-n p-n compensation">
			              
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

  $('#tab_content.compensation').load('<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_SALARY."/".$action."/".$employee_id."/".$token."/".$salt."/".$module;?>')
  $('.collection > a').click(function(event){

    event.preventDefault();

    $('#tab_content.compensation').isLoading();
    var href = $(this).attr('href');
    $.get(href, function(result){
      if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker').length)
         $('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');

      $('#tab_content.compensation').html(result);
      $('#tab_content.compensation').isLoading( "hide" );
    });

        $('.collection > a').removeClass('active');
        $(this).addClass('active');
  });
})
</script>