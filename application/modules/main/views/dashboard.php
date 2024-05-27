<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n">
	<input type="hidden" name="system_code" id="system_code" value="<?php echo $system_code ?>"/>
	<!--start container-->
	<div class="container p-t-n p-b-n">
		<?php if($system_code == 'HR' OR $system_code == 'TA' OR $system_code == 'PAYROLL'):?>
			<div class="row p-t-md">
				<div class="col s6">&nbsp</div>
				<div class="col s6">				
					<div class="row form-vertical form-styled form-basic">
			    		<div class="input-field col s4">
						      <label class="label position-right active">You are currently viewing:</label>
			    		</div>
			    		<div class="col s8">
			    			<select name="C-office_id" class="selectize form-filter" id="office_filter" placeholder="All offices..." onchange="office_filtering()">
			    				<option></option>			    				
			    				<?php foreach ($office_list as $off): ?>
									<option value="<?php echo $off['office_id'] ?>"<?php echo $office_id == $off['office_id'] ? 'selected="selected"' : ''?>><?php echo strtoupper($off['office_name']) ?></option>
								<?php endforeach;?>
			    			</select>
			    		</div>
			    	</div>	
				</div>
			</div>
		<?php endif;?>
		<div class="section">
			<div class="row hide-on-small-only">
                <div class="col s4 m4 l4">
                    <div class="card">
                    	<?php if($system_code == 'HR' OR $system_code == 'PAYROLL'):?>
	                        <div class="card-content green white-text">
							    <span class="white-text flaticon-user153 pull-right"></span>
		                    </div>
	                    <?php endif;?>
                        <div class="card-content green white-text p-t-md">
	                    	<span class="p-r-lg">
								<?php if($system_code == 'PORTAL' OR $system_code == 'TA' ):?>		                    	
		                    		<table class="table b-n">
		                    			<h1 class="m-n white-text text-lighten-5">
			                    			<tr class="b-n">
			                    				<td class="b-n p-n" style="font-size: 40px; vertical-align: bottom; width: 150px;"><?php echo !EMPTY($count_1['sl']) ? round($count_1['sl'], 3) : 0 ?></td>
			                    				<td class="b-r b-b-n b-l-n b-t-n"></td>
			                    				<td class="b-n p-n p-l-sm" style="font-size: 40px; vertical-align: bottom; width: 150px;"><?php echo !EMPTY($count_1['vl']) ? round($count_1['vl'], 3) : 0 ?></td>
			                    				<td class="b-n p-sm p-r-n pull-left" width="100">
				                    				<?php if($length <= 12): ?>
				                    					<span class="white-text flaticon-user153 pull-right"></span>
				                    				<?php else: ?>
				                    					<table class="b-n">
				                    						<tr class="b-n">
				                    							<td width="100" height="100" class="b-n"></td>
				                    						</tr>
				                    					</table>
				                    				<?php endif; ?>
			                    				</td>
			                    			</tr>
			                    			</h1>
			                    			<h4 class="m-n grey-text text-lighten-3">
			                    			<tr class="b-n p-n">
			                    				<td class="b-n p-n" style="font-size: 20px"><?php echo !EMPTY($title_1a) ? $title_1a : ' ' ?></td>
			                    				<td class="b-r b-b-n b-l-n b-t-n"></td>
			                    				<td colspan="2" class="b-n p-n  p-l-sm" style="font-size: 20px"><?php echo !EMPTY($title_1b) ? $title_1b : ' ' ?></td>
			                    			</tr>
		                    			</h4>
		                    		</table>		                    		
			                    <?php else:?>	
			                    	<h1 class="m-n white-text text-lighten-5">	                    		
			                    		<?php echo number_format($count_1['count']); ?>	 
			                    	</h1>     
			                    	<h4 class="m-n grey-text text-lighten-3">
			                    	    <?php echo !EMPTY($title_1) ? $title_1 : ' ' ?>	                    		
			                    	</h4>              		
			                    <?php endif;?>        	
		                    </span>
	                    </div>
                        <div class="card-action green darken-2">
                            <p class="green-text text-lighten-3"><?php echo !EMPTY($sub_title_1) ? $sub_title_1 : ' ' ?></p>
                        </div>
                    </div>
                </div>
                <div class="col s4 m4 l4">
                    <div class="card">
                        <div class="card-content orange darken-3 white-text">
						    <span class="white-text flaticon-user153 pull-right" style="width:0 auto"></span>
	                    </div>
                        <div class="card-content orange darken-3 white-text">
                        	<span class="p-r-lg">
	                    		<h1 class="m-n white-text text-lighten-5"><?php echo number_format($count_2['count']); ?></h1>
	                    		<h4 class="m-n grey-text text-lighten-3"><?php echo !EMPTY($title_2) ? $title_2 : ' ' ?></h4>
	                    	</span>
	                    </div>
                        <div class="card-action deep-orange darken-3">
                            <p class="green-text text-lighten-3"><?php echo !EMPTY($sub_title_2) ? $sub_title_2 : ' ' ?></p>
                        </div>
                    </div>
                </div>                            
                <div class="col s4 m4 l4">
                    <div class="card">
                        <div class="card-content blue-grey white-text">
						    <span class="white-text flaticon-user153 pull-right"></span>
	                    </div>
                        <div class="card-content blue-grey white-text">
	                    	<span class="p-r-lg">
	                    		<h1 class="m-n white-text text-lighten-5"><?php echo number_format(($system_code == 'HR') ? $count_3 : $count_3['count']); ?></h1>
	                    		<h4 class="m-n grey-text text-lighten-3"><?php echo !EMPTY($title_3) ? $title_3 : ' ' ?></h4>
	                    	</span>
	                    </div>
                        <div class="card-action blue-grey darken-3">
                            <p class="green-text text-lighten-3"><?php echo !EMPTY($sub_title_3) ? $sub_title_3 : ' ' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row show-on-small none">
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content  green white-text">
	                    	<span class="pull-right p-r-lg">
	                    	<?php if($system_code == 'PORTAL' OR $system_code == 'TA' ):?>	
	                    	<table class="table b-n">
	                    	<h1 class="m-n white-text text-lighten-5">
                    			<tr class="b-n">
                    				<td class="b-n p-n" style="font-size: 40px; vertical-align: bottom; width: 150px;"><?php echo !EMPTY($count_1['sl']) ? round($count_1['sl'], 3) : 0 ?></td>
                    				<td class="b-r b-b-n b-l-n b-t-n"></td>
                    				<td class="b-n p-n p-l-sm" style="font-size: 40px; vertical-align: bottom; width: 150px;"><?php echo !EMPTY($count_1['vl']) ? round($count_1['vl'], 3) : 0 ?></td>
                    			</tr>
                    			</h1>
                    			<h4 class="m-n grey-text text-lighten-3">
                    			<tr class="b-n p-n">
                    				<td class="b-n p-n" style="font-size: 20px"><?php echo !EMPTY($title_1a) ? $title_1a : ' ' ?></td>
                    				<td class="b-r b-b-n b-l-n b-t-n"></td>
                    				<td colspan="2" class="b-n p-n  p-l-sm" style="font-size: 20px"><?php echo !EMPTY($title_1b) ? $title_1b : ' ' ?></td>
                    			</tr>
                			</h4>
                			</table>
                			<?php else:?>	
                				<h1 class="m-n white-text text-lighten-5">	                    		
			                    	<?php echo number_format($count_1['count']); ?>	 
			                    </h1>     
			                    <h4 class="m-n grey-text text-lighten-3">
			                    	<?php echo !EMPTY($title_1) ? $title_1 : ' ' ?>	                    		
			                    </h4>   
                			<?php endif;?>	
	                    	</span>
						    <span class="white-text flaticon-user153 p-l-md"></span>
	                    </div>
                        <div class="card-action  green darken-2">
                            <p class="green-text text-lighten-3"><?php echo !EMPTY($sub_title_1) ? $sub_title_1 : ' ' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row show-on-small none">                            
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content orange darken-3 white-text">
	                    	<span class="pull-right p-r-lg">
	                    	<h1 class="m-n white-text text-lighten-5 pull-right"><?php echo number_format($count_2['count']); ?></h1>
	                    	<h4 class="m-n grey-text text-lighten-3"><?php echo !EMPTY($title_2) ? $title_2 : ' ' ?></h4>
	                    	</span>
						    <span class="white-text flaticon-user153 p-l-md"></span>
	                    </div>
                        <div class="card-action  deep-orange darken-3">
                            <p class="green-text text-lighten-3"><?php echo !EMPTY($sub_title_2) ? $sub_title_2 : ' ' ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row show-on-small none">
                <div class="col s12 m12 l12">
                    <div class="card">
                        <div class="card-content blue-grey white-text">
	                    	<span class="pull-right p-r-lg">
	                    	<h1 class="m-n white-text text-lighten-5 pull-right"><?php echo number_format($count_3['count']); ?></h1>
	                    	<h4 class="m-n grey-text text-lighten-3"><?php echo !EMPTY($title_3) ? $title_3 : ' ' ?></h4>
	                    	</span>
						    <span class="white-text flaticon-user153 p-l-md"></span>
	                    </div>
                        <div class="card-action  blue-grey darken-3">
                            <p class="green-text text-lighten-3"><?php echo !EMPTY($sub_title_3) ? $sub_title_3 : ' ' ?></p>
                        </div>
                    </div>
                </div>   
            </div>
			<div class="row <?php echo !EMPTY($hide_bar) ? 'hide' : ' ' ?>">
				<div class="col s6">
					<div class="card">
						<div class="card-content">
							<span class="card-title activator grey-text text-darken-4"><?php echo !EMPTY($bar_graph_1) ? $bar_graph_1 : ' ' ?><!-- <i class="material-icons right">more_vert</i> --></span>
						</div>
						<div class="card-image">
							<div id="legend_bar_chart1" class="legend left-bar"></div>
							<div class="m-lg m-b-sm"><canvas id="bar_canvas_1" style="max-height:350px; min-height:349px"></canvas></div>
						</div>
						<!-- <div class="card-reveal">
							<span class="card-title grey-text text-darken-4"><?php echo !EMPTY($bar_graph_sub_1) ? $bar_graph_sub_1 : ' ' ?><i class="material-icons right">close</i></span>
							<p><?php echo !EMPTY($bar_graph_summary_1) ? $bar_graph_summary_1 : ' ' ?></p>
						</div> -->
					</div>
				</div>
				<div class="col s6">
					<div class="card">
						<div class="card-content">
							<span class="card-title activator grey-text text-darken-4"><?php echo !EMPTY($bar_graph_2) ? $bar_graph_2 : ' ' ?><!-- <i class="material-icons right">more_vert</i> --></span>
						</div>
						<div class="card-image">
							<div id="legend_bar_chart2" class="legend left-bar"></div>
							<div class="m-lg m-b-sm"><canvas id="bar_canvas_2" style="max-height:350px; min-height:349px"></canvas></div>
						</div>
					<!-- 	<div class="card-reveal">
							<span class="card-title grey-text text-darken-4"><?php echo !EMPTY($bar_graph_sub_2) ? $bar_graph_sub_2 : ' ' ?><i class="material-icons right">close</i></span>
							<p><?php echo !EMPTY($bar_graph_summary_2) ? $bar_graph_summary_2 : ' ' ?></p>
						</div> -->
					</div>
				</div>	
			</div>
			<div class="row <?php echo (!EMPTY($hide_line)) ? 'hide' : ' ' ?>">
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<div id="legend_line_chart" class="legend left"></div>
							<span class="card-title activator grey-text text-darken-4">Late<!-- <i class="material-icons right">more_vert</i> --></span>
						</div>
						<div class="card-image">
							<div class="m-lg m-b-sm"><canvas id="canvas" height="200" width="800"></canvas></div>
						</div>
						<!-- <div class="card-reveal">
							<span class="card-title grey-text text-da

rken-4">Late<i class="material-icons right">close</i></span>
							<p>Here is some more information about this product that is only revealed once clicked on.</p>
						</div> -->
					</div>
				</div>	
			</div>
		</div>
	</div>
	<!--end container-->
</section>
<!-- END CONTENT -->
<script>

$("#office_filter").on("change", function(){
		var office_filter    = $(this).val();
		var system_code    	 = $('#system_code').val();
		
		window.location.href = "<?php echo base_url() . PROJECT_MAIN ?>/dashboard/get_dashboard/"+ system_code + '/' + office_filter;
	});

var lineChartData = {
	labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
	datasets : [
		{
			label: "<?php echo date('Y')?>",
			fillColor : "rgba(44, 178, 174, 0.8)",
			strokeColor : "rgba(44, 178, 174, 1)",
			pointColor : "rgba(44, 178, 174, 1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(44, 178, 174, 1)",
			data : [<?php echo $late_count['sum_jan'] ?>,<?php echo $late_count['sum_feb'] ?>,<?php echo $late_count['sum_mar'] ?>,<?php echo $late_count['sum_apr'] ?>,<?php echo $late_count['sum_may'] ?>,<?php echo $late_count['sum_jun'] ?>,<?php echo $late_count['sum_jul'] ?>,<?php echo $late_count['sum_aug'] ?>,<?php echo $late_count['sum_sep'] ?>,<?php echo $late_count['sum_oct'] ?>,<?php echo $late_count['sum_nov'] ?>,<?php echo $late_count['sum_dec'] ?>]
		}
	]

}

var barChartData_personnel = {
	labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
	datasets : [
		{
			label: "Personnel",
			fillColor : "rgba(138, 44, 44, 0.8)", 
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(220,220,220,1)",
			<?php if($system_code == CODE_HR): ?>
				data : [<?php echo $january['monthly_employee_count'] ?>,<?php echo $february['monthly_employee_count'] ?>,<?php echo $march['monthly_employee_count'] ?>,<?php echo $april['monthly_employee_count'] ?>,<?php echo $may['monthly_employee_count'] ?>,<?php echo $june['monthly_employee_count'] ?>,<?php echo $july['monthly_employee_count'] ?>,<?php echo $august['monthly_employee_count'] ?>,<?php echo $september['monthly_employee_count'] ?>,<?php echo $october['monthly_employee_count'] ?>,<?php echo $november['monthly_employee_count'] ?>,<?php echo $december['monthly_employee_count'] ?>]
			<?php endif; ?>
			<?php if($system_code == CODE_TA): ?>
				data : [<?php echo $january['count'] ?>,<?php echo $february['count'] ?>,<?php echo $march['count'] ?>,<?php echo $april['count'] ?>,<?php echo $may['count'] ?>,<?php echo $june['count'] ?>,<?php echo $july['count'] ?>,<?php echo $august['count'] ?>,<?php echo $september['count'] ?>,<?php echo $october['count'] ?>,<?php echo $november['count'] ?>,<?php echo $december['count'] ?>]
			<?php endif; ?>
			<?php if($system_code == CODE_PAYROLL): ?>
				data : [<?php echo $january['sum'] ?>,<?php echo $february['sum'] ?>,<?php echo $march['sum'] ?>,<?php echo $april['sum'] ?>,<?php echo $may['sum'] ?>,<?php echo $june['sum'] ?>,<?php echo $july['sum'] ?>,<?php echo $august['sum'] ?>,<?php echo $september['sum'] ?>,<?php echo $october['sum'] ?>,<?php echo $november['sum'] ?>,<?php echo $december['sum'] ?>]
			<?php endif; ?>
		}
	] 

}

var barChartData_cluster = {
	labels : ["January","February","March","April","May","June","July","August","September","October","November","December"],
	datasets : [
		{
			label: "Cluster",
			fillColor : "rgba(44, 178, 174, 0.8)",
			strokeColor : "rgba(44, 178, 174, 1)",
			pointColor : "rgba(44, 178, 174, 1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(44, 178, 174, 1)",
			data : [<?php echo count($january) ?>,<?php echo count($february) ?>,<?php echo count($march) ?>,<?php echo count($april) ?>,<?php echo count($may) ?>,<?php echo count($june) ?>,<?php echo count($july) ?>,<?php echo count($august) ?>,<?php echo count($september) ?>,<?php echo count($october) ?>,<?php echo count($november) ?>,<?php echo count($december) ?>]
		}
	]

}

window.onload = function(){
	var line_ctx = document.getElementById("canvas").getContext("2d");
	var myLine = new Chart(line_ctx).Line(lineChartData, {
		responsive: true
	});
	document.getElementById('legend_line_chart').innerHTML = myLine.generateLegend();

	var bar_ctx_1 = document.getElementById("bar_canvas_1").getContext("2d");
	var myBar1 = new Chart(bar_ctx_1).Bar(barChartData_personnel, {
		responsive: true
	});

	document.getElementById('legend_bar_chart1').innerHTML = myBar1.generateLegend();

	var bar_ctx_2 = document.getElementById("bar_canvas_2").getContext("2d");
	var myBar2 = new Chart(bar_ctx_2).Bar(barChartData_cluster, {
		responsive: true
	});
	document.getElementById('legend_bar_chart2').innerHTML = myBar2.generateLegend();
};
</script>