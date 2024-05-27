<div class="tabs-wrapper">
	<div class="col l12 m12 s12">
		<ul class="tabs teal">
			<li class="tab col l2"><a href="#tab_bir" class="active" onclick="load_index('tab_bir', 'statutory/get_tab_data/tab_bir', '<?php echo PROJECT_MAIN ?>')"><span>BIR</span></a></li>
			<li class="tab col l2"><a href="#tab_gsis" onclick="load_index('tab_gsis', 'statutory/get_tab_data/tab_gsis', '<?php echo PROJECT_MAIN ?>')"><span>GSIS</span></a></li>
			<li class="tab col l2"><a href="#tab_pagibig" onclick="load_index('tab_pagibig', 'statutory/get_tab_data/tab_pagibig', '<?php echo PROJECT_MAIN ?>')"><span>PAG-IBIG</span></a></li>
			<li class="tab col l2"><a href="#tab_philhealth"  onclick="load_index('tab_philhealth', 'statutory/get_tab_data/tab_philhealth', '<?php echo PROJECT_MAIN ?>')"><span>PhilHealth</span></a></li>
		</ul>
	</div>
</div>

<div id="tab_bir" class="tab-content col s12"></div>
<div id="tab_gsis" class="tab-content col s12"></div>
<div id="tab_pagibig" class="tab-content col s12"></div>
<div id="tab_philhealth" class="tab-content col s12"></div>
<script>
	load_initial_tab();
	tabs_init();
</script>