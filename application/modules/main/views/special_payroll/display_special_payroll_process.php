<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">        
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
        <div class="container">
            <div class="row">
                <div class="col s6 m6 l6">
                    <h5 class="breadcrumbs-title">Process Special Payroll</h5>
                    <ol class="breadcrumb m-n p-b-sm">
                        <?php get_breadcrumbs();?>
                    </ol>
                    <!-- <span>Manage all Deductions</span> -->
                </div>
                <div class="col s6 m6 l6 right-align">
                	<div class="row form-vertical form-styled form-basic">
                		<div class="input-field col s4">
						      <label class="label position-left active">You are currently viewing: </label>
                		</div>
                		<div class="col s8">
                			<select name="B-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
                				<option></option>
                				<?php 
                					foreach ($office_list as $key => $value) {
                						echo '<option value="' . $value['office_id'] . '">' . $value['office_name'] . '</option>';
                					}
                				?>
                			</select>
                		</div>
                	</div>
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
                            <li class="tab col s3 active"><a href="<?php echo base_url() . PROJECT_MAIN ."/payroll_general_tab/get_tab/tab_personnel_list/$action/$id/$token/$salt/$module/N";?>">Employee List</a></li>
                            <li class="tab col s3 active"><a href="<?php echo base_url() . PROJECT_MAIN ."/payroll_general_tab/get_tab/tab_compensation_list/$action/$id/$token/$salt/$module/N";?>">Compensation List</a></li>
                            <li class="tab col s3 active"><a href="<?php echo base_url() . PROJECT_MAIN ."/payroll_general_tab/get_tab/tab_deduction_list/$action/$id/$token/$salt/$module/N";?>">Deduction List</a></li>
                            <li class="tab col s3"><a href="<?php echo base_url() . PROJECT_MAIN ."/special_payroll_tab/get_tab/tab_process_status/$action/$id/$token/$salt/$module"?>">Process Status</a></li>
                            <li class="tab col s3"><a href="<?php echo base_url() . PROJECT_MAIN ."/payroll_general_tab/get_tab/tab_process_history/$action/$id/$token/$salt/$module/N"?>">Process History</a></li>
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

  $('#tab_content').load('<?php echo base_url() . PROJECT_MAIN ."/payroll_general_tab/get_tab/tab_personnel_list/$action/$id/$token/$salt/$module/N"?>')

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

