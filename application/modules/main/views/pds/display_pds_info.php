<?php
	$disabled = '';
	if($action == ACTION_ADD)
	{
		$disabled = 'disabled_link';
	}
?>
<input type="hidden" name="action" id="action" value="<?php echo $action ;?>">

<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		<div class="container">
			<div class="row">
				<div class="col s7 m7 l7">
					<h5 class="breadcrumbs-title"><?php echo SUB_MENU_PDS; ?></h5>
					<ol class="breadcrumb m-n p-b-sm">
						<?php get_breadcrumbs();?>
					</ol>
					<?php if($action != ACTION_ADD): ?>
						<div class="input-field">
							<button href="" class='btn m-t-n-sm m-r-n-lg ' data-tooltip='Add' data-position='bottom' data-delay='50' onclick='' id="print_pds"><i class="flaticon-printing23"></i> Print PDS</button> 						
						</div>
					<?php endif; ?>
				</div>

				<?php if($action != ACTION_ADD) :?>
				<div class="col l5 m5 s5 p-t-lg user-avatar">
					<div class="row m-n ">
						<?php 
                            /* GET USER AVATAR */
                            $emp_avatar_src = base_url() . PATH_USER_UPLOADS . $personal_info['photo'];
                            $emp_avatar_src = @getimagesize($emp_avatar_src) ? $emp_avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
                          ?>
                        <img class="circle" width="65" height="65" src="<?php echo  $emp_avatar_src?>"/>  
                        <label class="dark font-xl"><?php echo ucwords($personal_info['fullname']); ?></label><br>
                        <label class="font-lg"><?php echo $personal_info['position_name']; ?></label><br>
                        <label class="font-md"><?php echo $personal_info['name']; ?></label>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	<!--breadcrumbs end-->

	<!--start container-->
	<div class="container p-t-n">
		<div class="section panel p-lg  p-b-sm">
			<!--start section-->
			<div id="article_tab" >
				<div class="row m-t-n">				
					<ul class="collection col l3 s12 b-n m-r-md m-t-n-xs">
						<div class="collection m-n b-n pds-nav">
							<a class="collection-item p-l-n active" href="<?php echo base_url() . PROJECT_MAIN ."/pds_personal_info/get_pds_personal_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-user153"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_PERSONAL_INFO; ?></span><span class="pull-right"><i class="flaticon-warning34 red-text none"></i></span></a>
							<?php if($action != ACTION_ADD) : ?>
							<!--<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_identification_info/get_pds_identification_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-user154"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_IDENTIFICATION; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_contact_info/get_pds_contact_info/"."/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-phone364"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo "Contact/Address Information"; ?></span></a>-->
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_family_info/get_pds_family_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-group66"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_FAMILY; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_education_info/get_pds_education_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-graduate31"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_EDUCATION; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_government_exam_info/get_pds_government_exam_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-star169"></i> <span class="hide-display show-on-hover m-l-sm">Eligibility</span></a>
							<a id='work_experience_tab' class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_work_experience_info/get_pds_work_experience_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-travel18"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_WORK_EXPERIENCE; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_profession_info/get_pds_profession_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-travel18"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_PROFESSION; ?></span></a>						
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_voluntary_work_info/get_pds_voluntary_work_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-lifesaver5"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_VOLUNTARY_WORK; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_trainings_info/get_pds_trainings_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_TRAININGS; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_other_information_info/get_pds_other_information_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-cubes3"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_OTHER_INFO; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_questionnaire_info/get_pds_questionnaire_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-help17"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_QUESTIONNAIRE; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_references_info/get_pds_references_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-like73"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_REFERENCES; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_declaration_info/get_pds_declaration_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-hammer53"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo SUB_MENU_DECLARATION; ?></span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_supporting_documents_info/get_pds_supporting_documents_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-text140"></i> <span class="hide-display show-on-hover m-l-sm">Supporting Documents</span></a>
							<a class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds_position_description_info/get_pds_position_description_info/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-clipboard95"></i> <span class="hide-display show-on-hover m-l-sm">Position Description</span></a>
							<?php endif; ?>
							<?php if($module == MODULE_PERSONNEL_PORTAL && $action != ACTION_ADD):?>
							<a id = "record_changes" class="collection-item p-l-n <?php echo $disabled ?>" href="<?php echo base_url() . PROJECT_MAIN ."/pds/get_tab/".PDS_RECORD_CHANGES."/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-text142"></i> <span class="hide-display show-on-hover m-l-sm">Record Changes</span></a>
							<?php endif;?>
							<a class="collection-item p-l-n hide" href="<?php echo base_url() . PROJECT_MAIN ."/pds/get_tab/".PDS_CHECK_LIST."/".$action."/".$id."/".$token."/".$salt."/".$module?>"><i class="flaticon-verify8"></i> <span class="hide-display show-on-hover m-l-sm">Check List</span></a>
						</div>	
					</ul>
					<div id="tab_content" class="panel col l9 s8 m-r-n-md m-n p-n p-r-md ">

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

$('#print_pds').click(function() {
	window.open("<?php echo base_url() . PROJECT_MAIN .'/pds/print_pds/'.$action.'/'.$id.'/'.$token.'/'.$salt.'/'.$module.'/Print PDS' ?>", '_blank');
});

$(function (){
	var action = $("#action").val();

	
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/pds_personal_info/get_pds_personal_info/".$action."/".$id."/".$token."/".$salt."/".$module;?>')
	$('.pds-nav > a').click(function(event){
		event.preventDefault();
		if($(this).hasClass('disabled_link') == false && action != <?php echo ACTION_ADD ?>)
		{
			$('#tab_content').isLoading();
			var href = $(this).attr('href');
			$.get(href, function(result){
				// if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker') === 1)
				$('.datepicker,.datepicker_start,.datepicker_end,.timepicker').datetimepicker('destroy');
				$('#tab_content').html(result);
	            $('#tab_content').isLoading( "hide" );
			});
			
	        $('.pds-nav > a').removeClass('active');
	        $(this).addClass('active');
	    }
	});
});
</script>