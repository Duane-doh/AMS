<div class="page-title">
  <ul id="breadcrumbs">
	<li><a href="#">Home</a></li>
	<li><a href="#" class="active">Dashboard</a></li>
  </ul>
  <div class="row m-b-n">
	<div class="col s12 p-r-n">
	  <h5>Dashboard</h5>
	</div>
  </div>
</div>

<div class="row m-t-lg">
  <div class="col s9">
	<div class="card">
	  <div class="card-image waves-effect waves-block waves-light">
		<div class="m-lg m-b-sm"><canvas id="canvas" height="300" width="800"></canvas></div>
	  </div>
	  <div class="card-content">
	    <span class="card-title activator grey-text text-darken-4">Programmed Appropriation<i class="material-icons right">more_vert</i></span>
	    <p><a href="#">This is a link</a></p>
	  </div>
	  <div class="card-reveal">
	    <span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
	    <p>Here is some more information about this product that is only revealed once clicked on.</p>
	  </div>
	</div>
	
	<div class="row m-t-lg">
	  <div class="col s4">
		<div class="card">
		  <div class="card-image waves-effect waves-block waves-light">
			<div class="m-sm m-b-xs center-align"><canvas id="chart-area" width="180" height="200"/></div>
		  </div>
		  <div class="card-content">
			<span class="card-title activator grey-text text-darken-4 font-lg">Annual Investment Plan<i class="material-icons right">more_vert</i></span>
			<p><a href="#">Breakdown According to Sector</a></p>
		  </div>
		  <div class="card-reveal">
			<span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
			<p>Here is some more information about this product that is only revealed once clicked on.</p>
		  </div>
		</div>
	  </div>
	  <div class="col s4">
		<div class="card">
		  <div class="card-image waves-effect waves-block waves-light">
			<div class="m-sm m-b-xs center-align"><canvas id="chart-area1" width="180" height="200"/></div>
		  </div>
		  <div class="card-content">
			<span class="card-title activator grey-text text-darken-4 font-lg">Proposed Budget<i class="material-icons right">more_vert</i></span>
			<p><a href="#">Breakdown According to Expense Class</a></p>
		  </div>
		  <div class="card-reveal">
			<span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
			<p>Here is some more information about this product that is only revealed once clicked on.</p>
		  </div>
		</div>
	  </div>
	  <div class="col s4">
		<div class="card">
		  <div class="card-image waves-effect waves-block waves-light">
			<div class="m-sm m-b-xs center-align"><canvas id="chart-area2" width="180" height="200"/></div>
		  </div>
		  <div class="card-content">
			<span class="card-title activator grey-text text-darken-4 font-lg">Approved Budget<i class="material-icons right">more_vert</i></span>
			<p><a href="#">Breakdown According to Expense Class</a></p>
		  </div>
		  <div class="card-reveal">
			<span class="card-title grey-text text-darken-4">Card Title<i class="material-icons right">close</i></span>
			<p>Here is some more information about this product that is only revealed once clicked on.</p>
		  </div>
		</div>
	  </div>
	</div>
  </div>
  
  <div class="col s3">
    <div class="sidebar list">
	  <h5 class="sidebar-title">To Dos</h5>
	  <ul>
	    <li class="sidebar-subtitle">Today <span>- 11/06</span></li>
	    <li>
	      <input type="checkbox" name="to_dos" id="to_dos1" />
		  <label for="to_dos1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
	    </li>
	    <li>
	      <input type="checkbox" name="to_dos" id="to_dos2" />
		  <label for="to_dos2">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
	    </li>
	    <li>
	      <input type="checkbox" name="to_dos" id="to_dos3" />
		  <label for="to_dos3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
	    </li>
	    <li>
	      <input type="checkbox" name="to_dos" id="to_dos1" />
		  <label for="to_dos1">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
	    </li>
	    <li class="sidebar-subtitle">Yesterday <span>- 11/05</span></li>
	    <li>
	      <input type="checkbox" name="to_dos" id="to_dos2" />
		  <label for="to_dos2">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
	    </li>
	    <li>
	      <input type="checkbox" name="to_dos" id="to_dos3" />
		  <label for="to_dos3">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</label>
	    </li>
      </ul>
    </div>
  </div>
</div>


<script>
var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
var lineChartData = {
	labels : ["January","February","March","April","May","June","July"],
	datasets : [
		{
			label: "My First dataset",
			fillColor : "rgba(220,220,220,0.2)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(220,220,220,1)",
			data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
		},
		{
			label: "My Second dataset",
			fillColor : "rgba(151,187,205,0.2)",
			strokeColor : "rgba(151,187,205,1)",
			pointColor : "rgba(151,187,205,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(151,187,205,1)",
			data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
		}
	]

}

window.onload = function(){
}

var pieData = [
{
	value: 300,
	color:"#F7464A",
	highlight: "#FF5A5E",
	label: "Red"
},
{
	value: 50,
	color: "#46BFBD",
	highlight: "#5AD3D1",
	label: "Green"
},
{
	value: 100,
	color: "#FDB45C",
	highlight: "#FFC870",
	label: "Yellow"
},
{
	value: 40,
	color: "#949FB1",
	highlight: "#A8B3C5",
	label: "Grey"
},
{
	value: 120,
	color: "#4D5360",
	highlight: "#616774",
	label: "Dark Grey"
}

];

window.onload = function(){
	var ctx = document.getElementById("canvas").getContext("2d");
	window.myLine = new Chart(ctx).Line(lineChartData, {
		responsive: true
	});
	
	var ctx1 = document.getElementById("chart-area").getContext("2d");
	var ctx2 = document.getElementById("chart-area1").getContext("2d");
	var ctx3 = document.getElementById("chart-area2").getContext("2d");
	
	window.myPie = new Chart(ctx1).Pie(pieData);
	window.myPie = new Chart(ctx2).Pie(pieData);
	window.myPie = new Chart(ctx3).Pie(pieData);
};
</script>