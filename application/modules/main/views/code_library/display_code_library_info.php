<input type="hidden" name="action_id" value="<?php echo $action_id ;?>">

<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n list">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s6 m6 l6">
					<h5 class="breadcrumbs-title"><?php echo SUB_MENU_CODE_LIBRARY; ?></h5>
					<ol class="breadcrumb m-n p-b-sm">
						<?php get_breadcrumbs();?>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!--breadcrumbs end-->

	<!--start container-->
	<div class="container p-t-n">
		<div class="section panel p-lg p-t-sm p-b-sm">
		<!--start section-->

			<div id="article_tab">
				<div class="row p-t-sm">
					<ul class="collection col l2 s12 m-n m-r-md p-n b-n m-b-md">
						<div class="collection m-n b-n cl-nav">
							<?php if($page == CODE_LIBRARY_HUMAN_RESOURCES):?>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/education_degree/initialize/".$action_id?>"><i class="flaticon-graduate31"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_EDUCATION_DEGREE; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/educational_level/initialize/".$action_id?>"><i class="flaticon-cubes3"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_EDUCATIONAL_LEVEL; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/eligibility/initialize/".$action_id?>"><i class="flaticon-checkmark21"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_ELIGIBILITY_TITLE; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/employment_status/initialize/".$action_id?>"><i class="flaticon-group65"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_EMPLOYMENT_STATUS; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/branch/initialize/".$action_id?>"><i class="flaticon-building69"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_BRANCH; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/separation_mode/initialize/".$action_id?>"><i class="flaticon-link55"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SEPARATION_MODE; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/personnel_movement/initialize/".$action_id?>"><i class="flaticon-event10"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_PERSONNEL_MOVEMENT; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/plantilla/initialize/".$action_id?>"><i class="flaticon-hierarchical9"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_PLANTILLA; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/position/initialize/".$action_id;?>"><i class="flaticon-star169"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_POSITION; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/salary_schedule/initialize/".$action_id?>"><i class="flaticon-money128"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SALARY_SCHEDULE; ?></span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/school/initialize/".$action_id?>"><i class="flaticon-book202"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SCHOOL; ?></span></a>
							<?php endif;?>

							<?php if($page == CODE_LIBRARY_PAYROLL):?>		
							<a class ="collection-item p-l-n active green" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/bank/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_BANK; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/bir/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_BIR_TABLE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/fund_source/initialize/".$action_id?>"><i class="flaticon-money128"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_FUND_SOURCE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/gsis/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_GSIS_TABLE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/pagibig/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_PAGIBIG_TABLE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/philhealth/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_PHILHEALTH_TABLE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/sss/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SSS_TABLE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/remittance_payee/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_REMITTANCE_PAYEE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/remittance_type/initialize/".$action_id?>"><i class="flaticon-weekly18"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_REMITTANCE_TYPE; ?></span></a>
							<a class ="collection-item p-l-n grey-text hide" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/voucher/initialize/".$action_id?>"><i class="flaticon-giftbox57"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_VOUCHER; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/compensation_type/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_COMPENSATION; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/deduction_type/initialize/".$action_id?>"><i class="flaticon-minus100"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_DEDUCTION_TYPE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/responsibility_center/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_RESPONSIBILITY_CENTER; ?></span></a>
							<!-- ===================== jendaigo : start : add uacs object code library ============= -->
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/uacs_object/initialize/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_UACS_OBJECT; ?></span></a>
							<!-- ===================== jendaigo : end : add uacs object code library ============= -->
							<?php endif;?>

							<?php if($page == CODE_LIBRARY_ATTENDANCE):?>
							<a class ="collection-item p-l-n grey-text active green" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_ta/holiday_type/initialize/".$action_id?>"><i class="flaticon-clipboard95"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_HOLIDAY_TYPE; ?></span></a>	
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_ta/leave_type/initialize/".$action_id?>"><i class="flaticon-left195"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_LEAVE_TYPE; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_ta/work_calendar/initialize/".$action_id?>"><i class="flaticon-weekly18"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_WORK_CALENDAR; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_ta/work_schedule/initialize/".$action_id?>"><i class="flaticon-interface57"></i> <span class="hide-display show-on-hover m-l-sm">Work Schedule</span></a>
							<!-- ===========================davcorrea :start : add computation table code library============= -->
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_ta/computation_table/initialize/".$action_id?>"><i class="flaticon-weekly18"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_COMPUTATION_TABLE; ?></span></a>
							<!-- ===========================davcorrea :end : add computation table code library============= -->
							<?php endif;?>

							<?php if($page == CODE_LIBRARY_SYSTEM):?>
							<a class ="collection-item p-l-n grey-text active green" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_system/check_list/initialize/".$action_id?>"><i class="flaticon-checkmark21"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_DOCUMENT_CHECKLIST; ?></span></a>	
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_system/dropdown/initialize/".$action_id?>"><i class="flaticon-sort49"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_DROPDOWN; ?></span></a>	
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_system/supp_doc_type/initialize/".$action_id?>"><i class="flaticon-file85"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SUPP_DOC_TYPE; ?></span></a>	
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_system/signatories/initialize/".$action_id?>"><i class="flaticon-text142"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SIGNATORIES; ?></span></a>
							<a class ="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library_system/sys_param/initialize/".$action_id?>"><i class="flaticon-gears5"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo CODE_LIBRARY_SYSTEM_PARAMETER; ?></span></a>
							<?php endif;?>

						</div>	
					</ul>
					<div id="tab_content" class="panel col l10 s8 m-r-n-md m-n p-n p-r-md">

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
	<?php if($page == CODE_LIBRARY_PAYROLL):?>
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/code_library_payroll/bank/initialize/".$action_id;?>')
	<?php elseif($page == CODE_LIBRARY_SYSTEM):?>
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/code_library_system/check_list/initialize/".$action_id;?>')
	<?php elseif($page == CODE_LIBRARY_ATTENDANCE):?>
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/code_library_ta/holiday_type/initialize/".$action_id;?>')
	<?php else:?>
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/code_library_hr/education_degree/initialize/".$action_id;?>')
	<?php endif;?>
	$('.cl-nav > a').click(function(event){
		event.preventDefault();
		$('.xdsoft_datetimepicker').hide();
		$('#tab_content').isLoading();
		var href = $(this).attr('href');
		$.get(href, function(result){
			<?php if($page == CODE_LIBRARY_ATTENDANCE):?>
			$('#group_color').ColorPicker('destroy');
			<?php endif;?>
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