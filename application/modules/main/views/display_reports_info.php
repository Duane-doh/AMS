<?php
	$data_id = 'modal_pds_upload/' . ACTION_ADD;
?>
<input type="hidden" name="action_id" value="<?php echo $action_id ;?>">

<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n list">

	<!--breadcrumbs start-->
	<div id="breadcrumbs-wrapper" class=" grey lighten-3"> 
          <div class="container">
            <div class="row">
             <div class="col s6 m6 l6">
					<ol class="breadcrumb m-n p-b-sm">
						<?php get_breadcrumbs();?>
					</ol>
					<h5 class="breadcrumbs-title"><?php echo SUB_MENU_REPORTS; ?></h5>
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
							<a class="collection-item p-l-n active green" href="<?php echo base_url() . PROJECT_MAIN ."/reports/get_tab/academic_honor/".$action_id?>"><i class="flaticon-sport17"></i> <span class="hide-display show-on-hover m-l-sm">Demographics</span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library/get_tab/appointment_status/".$action_id?>"><i class="flaticon-weekly18"></i> <span class="hide-display show-on-hover m-l-sm">Organization Structure</span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library/get_tab/appointment_type/".$action_id?>"><i class="flaticon-weekly18"></i> <span class="hide-display show-on-hover m-l-sm">Welfare and Benefits</span></a>
							<a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/code_library/get_tab/compensation_type/".$action_id?>"><i class="flaticon-label29"></i> <span class="hide-display show-on-hover m-l-sm">Membership</span></a>
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
	$('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/code_library/get_tab/academic_honor/".$action_id;?>')
	
	$('.cl-nav > a').click(function(event){

		event.preventDefault();

		$('#tab_content').isLoading();
		var href = $(this).attr('href');
		$.get(href, function(result){
			$('#tab_content').html(result);
			$('#tab_content').isLoading( "hide" );
		});
        $('.cl-nav > a').removeClass('active green');
        $('.cl-nav > a').addClass('grey-text').removeClass('white-text');
        $(this).addClass('active green white-text');
	});

})
</script>