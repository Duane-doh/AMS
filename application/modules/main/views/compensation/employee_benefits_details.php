<input type="hidden" name="action_id" value="<?php echo $action_id ;?>">

<!-- START CONTENT -->
<section id="content" class="p-t-n ">        
        <!--breadcrumbs start-->
  <div id="breadcrumbs-wrapper" class=" grey lighten-3">
    <div class="container">
      <div class="row">
        <div class="col s12 m12 l12">
          <ol class="breadcrumb m-n p-b-sm">
              <li><a href="index.html">Human Resources</a></li>
              <li><a class="active">Compensations</a></li>
          </ol>
          <h5 class="breadcrumbs-title"> Compensations </h5>
        </div>
      </div>
    </div>
  </div>
        <!--breadcrumbs end-->
        
        <!--start container-->
  <div class="container">
    <div class="section panel p-sm p-t-n">
    <!--start section-->
      <div id="article_tab teal">
        <div class=" row teal">
          <div class="col s8 ">
              
            </div>
            <div id="tab_content" class="panel col s12 p-b-xs p-t-lg"></div>
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

  $('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/compensation/get_tab/benefit_employee_list/".$action."/".$token."/".$salt."/".$module;?>')

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
