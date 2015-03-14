<?php

global $reftracknotif;
$refuserlist = new Reftrack_Users_List_Table(); 

?>

<div class="reftrack-container">
	<div class="reftrack-users">
		<h2><span class="dashicons dashicons-groups"></span> Referral Links</h2>
		
		<!--- /// add new user form /// -->
		<div class="reftrack-users-top">
			<p><a href="#" class="button" id="reftrack-addnewbtn">Add New</a></p>
		</div>
		
		<?php $reftracknotif->displaymsg(); ?>
		
		<div class="reftrack-frame reftrack-addnewuser">
			<form method="post" action="<?php echo admin_url('admin.php?page=reftrack-users'); ?>">
			<table>
			<tr><td>Username: </td><td><input type="text" name="username"></td></tr>
			<tr><td>Tracking Code: </td><td><input type="text" name="tracking_code"></td></tr>
			</table>
			<br>
			<input type="hidden" name="reftracksave" value="addnewuser">
			<input type="submit" value="Insert" class="button button-primary">
			</form>
		</div>
		<!--- /// add new user form end /// -->
		
		<?php if(isset($_GET['reftracktable']) && isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['username'])) { ?>
			<div class="reftrack-frame"  id="confirmremovebox">
				<h4>Removing users from here will also remove all logged visits by this user. Are you sure you want to delete selected user(s)?</h4>
					<form method="get" style="display:inline">
						<input type="submit" class="button" value="Confirm Removal">
						<?php 
							foreach($_GET as $key => $val) { 
								if(is_array($val)) { 
									foreach($val as $k => $v) { 
										echo '<input type="hidden" name="'.$key.'[]" value="'.$v.'">';
									}
								} else { 
									echo '<input type="hidden" name="'.$key.'" value="'.$val.'">';
								}
							}
						 ?>
					<input type="hidden" name="confirmuserdel" value="1">
					</form> <a href="#" class="button button-primary" onclick="console.log(jQuery('#confirmremovebox').fadeOut())">Cancel</a>
			</div>
		<?php } ?>

		<div class="reftrack-visitlist">
			<form id="reftrack-filter" method="get">
				<?php 
				
				$refuserlist->prepare_items();
				$refuserlist->display();
				
				?>
				<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
				<input type="hidden" name="reftracktable" value="1">
			</form>
		</div>
		
	</div>
</div>


