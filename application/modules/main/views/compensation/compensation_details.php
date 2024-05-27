<form id="form_compensation_details">
	<input type="hidden" id="employee_id" name="employee_id" value="<?php echo $employee_id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>

	<input type="hidden" name="action_id" id="action_id" value="<?php echo $action_id ;?>">

	<!-- START CONTENT -->
	<section id="content" class="p-t-n m-t-n">

		<!--breadcrumbs start-->
		<div id="breadcrumbs-wrapper" class=" grey lighten-3">
			<div class="container">
				<div class="row">
					<div class="col s7 m7 l7">
						<h5 class="breadcrumbs-title"> Compensations </h5>
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
			<div class="section panel p-lg p-t-xxxs">
			<!--start section-->

			<div class="col l7 m10 s7 right-align">
			</div>
			<div id="article_tab">
				<div class="row m-t-md">
					<ul class="collection col l3 s12 m-n m-r-md p-n b-n m-b-md">
						<div class="collection m-n b-n pds-nav">
							<a class="collection-item p-l-n active" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_SALARY."/".$action."/".$employee_id."/".$token."/".$salt."/".$module;?>"><i class="flaticon-money128"></i> <span class="hide-display show-on-hover m-l-sm">Salary</span></a>
							<!-- ====================== jendaigo : start : add employee bank account and responsibility code encoding ============= -->
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/employee_bank/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission?>"><i class="flaticon-user154"></i> <span class="hide-display show-on-hover m-l-sm">Bank Account</span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/employee_responsibility_code/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm">Responsibility Code</span></a>
							<!-- ====================== jendaigo : start : end employee bank account and responsibility code encoding ============= -->
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_BENEFITS."/".$action."/".$employee_id."/".$token."/".$salt."/".$module."/".$has_permission?>"><i class="flaticon-credit92"></i> <span class="hide-display show-on-hover m-l-sm">Benefits</span></a>
							<?php if($module == MODULE_PERSONNEL_PORTAL) : ?>
									<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/request_certificate/".$action."/".$employee_id."/".$token."/".$salt."/".$module?>"><i class="flaticon-add175"></i> <span class="hide-display show-on-hover m-l-sm">Certificate Request</span></a>
							<?php endif; ?>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/compensation_history/".$action."/".$employee_id."/".$token."/".$salt."/".$module?>"><i class="flaticon-list82"></i> <span class="hide-display show-on-hover m-l-sm">Compensation History</span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/payslip_history/".$action."/".$employee_id."/".$token."/".$salt."/".$module?>" target="_blank"><i class="flaticon-horizontal38"></i> <span class="hide-display show-on-hover m-l-sm">Payslip History</span></a>
						</div>	
					</ul>
					<div id="tab_content" class="panel col l9 s8 m-r-n-md m-n p-n p-r-md compensation">

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
</form>

<script type="text/javascript">
$(function (){
	var action_id = $("#action_id").val();
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/compensation/get_employee_tab/".CP_SALARY."/".$action."/".$employee_id."/".$token."/".$salt."/".$module;?>')
	$('.pds-nav > a').click(function(event){
		event.preventDefault();
		if($(this).hasClass('disabled_link') == false && action_id != <?php echo ACTION_ADD ?>)
		{
			$('#tab_content').isLoading();
			var href = $(this).attr('href');
			$.get(href, function(result){
				$('#tab_content').html(result);
				$('#tab_content').isLoading( "hide" );
			});
	        $('.pds-nav > a').removeClass('active');
	        $(this).addClass('active');
	    }
	});
})
</script>