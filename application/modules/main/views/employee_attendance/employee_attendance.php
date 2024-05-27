<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s17 m7 l7">
                    <h5 class="breadcrumbs-title">Employee Attendance</h5>
                     <ol class="breadcrumb m-n ">
                        <?php get_breadcrumbs();?>
                    </ol>
                </div>
                 <div class="col l5 m5 s5 p-t-sm user-avatar">
                    <div class="row m-n">
                        <?php 
                            /* GET USER AVATAR */
                            $emp_avatar_src = base_url() . PATH_USER_UPLOADS . $personal_info['photo'];
                            $emp_avatar_src = @getimagesize($emp_avatar_src) ? $emp_avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
                          ?>
                        <img class="circle" width="65" height="65" src="<?php echo  $emp_avatar_src?>"/> 
                        <label class="dark font-xl"><?php echo $personal_info['fullname']; ?></label><br>
                        <label class="font-lg"><?php echo $personal_info['agency_employee_id']; ?></label><br>
                        <label class="font-md"><?php echo $personal_info['name']; ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

    <!--start container-->
    <div class="container">
        <div class="section panel p-r-lg" style="min-height:410px">
        <!--start section-->

            <div id="article_tab">
                <div class="row">
                    <ul class="collection col l2 s12 m-n m-r-md p-n b-n m-b-md">
                        <div class="collection m-n b-n cl-nav">
                            <a id="attendance_dtr_tab" class="collection-item p-l-n grey-text active green" href="<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_time_logs/".$action."/".$id."/".$token."/".$salt."/".$module; ?>"><i class="flaticon-weekly18"></i> <span class="hide-display show-on-hover m-l-sm">Daily Time Record</span></a>
                            <a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_leave/".$action."/".$id."/".$token."/".$salt."/".$module; ?>"><i class="flaticon-safe21"></i> <span class="hide-display show-on-hover m-l-sm">Leaves</span></a>
                            <a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/employee_work_schedule/get_schedule/".$action."/".$id."/".$token."/".$salt."/".$module; ?>"><i class="flaticon-interface57"></i> <span class="hide-display show-on-hover m-l-sm">Work Schedule</span></a>
                       </div>	
                    </ul>
                    <div id="tab_content" class="panel col l10 s12 m-r-n-md m-n p-n">

                    </div>
                </div>
            </div>

        <!--end section-->              
        </div>
    </div>
    <!--end container-->

</section>
<!-- END CONTENT -->
<script type="text/javascript">
$(function (){

	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/employee_attendance/get_time_logs/".$action."/".$id."/".$token."/".$salt."/".$module;?>')

	$('.cl-nav > a').click(function(event){

		event.preventDefault();

		$('#tab_content').isLoading();
		var href = $(this).attr('href');
		$.get(href, function(result){
            if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker').length > 0)
            {
                $('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');
            }            
			$('#tab_content').html(result);
			$('#tab_content').isLoading( "hide" );
		});
        $('.cl-nav > a').removeClass('active green');
        $('.cl-nav > a').addClass('grey-text').removeClass('white-text');
        $(this).addClass('active green white-text');
	});

})
</script>