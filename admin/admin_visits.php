<?php

global $reftracknotif;
$refvisitlist = new Reftrack_Visits_List_Table(); 

?>
<div class="reftrack-container">
	<div class="reftrack-users">
		<h2><span class="dashicons dashicons-chart-pie"></span> Visits</h2>

		<div class="reftrack-userslist">
			<?php
			
			$refvisitlist->prepare_items();
			$refvisitlist->display();
			
			?>
		</div>
	</div>
</div>
