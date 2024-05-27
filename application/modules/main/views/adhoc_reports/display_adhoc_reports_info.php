<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">

    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class="grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h5 class="breadcrumbs-title"><?php echo SUB_MENU_ADHOC_REPORTS; ?></h5>
                    <ol class="breadcrumb m-n p-b-sm">
                        <li><a href="index.html">Home</a></li>
                        <li><a class="active"><?php echo SUB_MENU_ADHOC_REPORTS; ?></a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

    <!--start container-->
    <div class="container">
        <div class="section panel p-r-lg p-t-n" style="min-height:410px">
        <!--start section-->

            <div id="article_tab">
                <div class="row">
                    <ul class="collection col l2 s12 m-n m-r-md p-n b-n m-b-md">
                        <div class="collection m-n b-n cl-nav">
                             <a class="collection-item p-l-n grey-text active green" href="<?php echo base_url() . PROJECT_MAIN ."/adhoc_reports/get_tab/table_group"?>"><i class="flaticon-database46"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo TAB_TABLE_GROUP; ?></span></a>
                            <a class="collection-item p-l-n grey-text" href="<?php echo base_url() . PROJECT_MAIN ."/adhoc_reports/get_tab/data_download" ?>"><i class="flaticon-download158"></i> <span class="hide-display show-on-hover m-l-sm"><?php echo TAB_DATA_DOWNLOAD; ?></span></a>
                        </div>	
                    </ul>
                    <div id="tab_content" class="panel col l10 s8 m-r-n-md m-n p-n">

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

    $('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/adhoc_reports/get_tab/table_group";?>')

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