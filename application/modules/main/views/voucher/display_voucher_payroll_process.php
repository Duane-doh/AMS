
<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">        
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s6 m6 l6">
                    <ol class="breadcrumb m-n p-b-sm">
                        <?php get_breadcrumbs();?>
                    </ol>
                    <h5 class="breadcrumbs-title">Process Employee Voucher</h5>
                    <!-- <span>Manage all Deductions</span> -->
                </div>
                <div class="col s6 m6 l6 right-align">
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

    <!--start container-->
    <div class="container p-t-n">
        <div class="section panel p-sm p-t-n">
            <!--start section-->
            <div id="article_tab teal">
                <div class=" row teal">
                    <div class="col s12">
                        <ul class="tabs teal">
                            <li class="tab col s6 active"><a id="link_voucher_process" href="<?php echo base_url() . PROJECT_MAIN ."/voucher_process/get_tab/tab_voucher_process/$action/$id/$token/$salt/$module"?>">Voucher Status</a></li>
                            <li class="tab col s6"><a href="<?php echo base_url() . PROJECT_MAIN ."/voucher_process/get_tab/tab_voucher_history/$action/$id/$token/$salt/$module"?>">Voucher History</a></li>
                        </ul>
                    </div>
                    <div id="tab_content" class="panel col s12 p-b-xs p-t-xxs p-lg"></div>
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

  $('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/voucher_process/get_tab/tab_voucher_process/$action/$id/$token/$salt/$module"?>')

  $('.tabs > li > a').click(function(event){

    event.preventDefault();

    $('#tab_content').isLoading();
    var href = $(this).attr('href');
    $.get(href, function(result){
      $('#tab_content').html(result);
       $('#tab_content').isLoading( "hide" );
    });

        $('.tabs > li > a').removeClass('active');
        $(this).addClass('active');
  });
})
</script>

