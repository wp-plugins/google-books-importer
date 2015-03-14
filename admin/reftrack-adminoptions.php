<?php

global $affiliatesdb, $visitsdb;
// reftrack notification object 
$reftracknotif = new reftrack_notifcations();
$saveaction = isset($_REQUEST['reftracksave']) ? $_REQUEST['reftracksave'] : false;

if(isset($_REQUEST['reftracktable'])) { 
	if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete') { 
		$saveaction = 'removeuser';
	}
}

switch($saveaction) { 
	case 'addnewuser': { 
		
		if(isset($_POST['username']) && !empty($_POST['username'])) { 
			$username = trim($_POST['username']);
			if(is_object($affiliatesdb->get_user_by_username($username))) { 
				$reftracknotif->error('Username already exists');
			}
		} else { 
			$reftracknotif->error('Username field can not be empty');
		}
			
		if(isset($_POST['tracking_code']) && !empty($_POST['tracking_code'])) { 
			$tracking_code = trim($_POST['tracking_code']);
			if(is_object($affiliatesdb->get_user_by_trackingvalue($tracking_code))) { 
				$reftracknotif->error('Username with this tracking value already exists');
			}
		} else { 
			$reftracknotif->error('Tracking code can not be empty');
		}

		if(!$reftracknotif->has_error) { 
			if($affiliatesdb->insert_user($username, $tracking_code)) { 
				$reftracknotif->success('New user has been added');
			} else { 
				$reftracknotif->error('User could not be inserted to database');
			}
		}
		
	} break;
	case 'removeuser': { 
		
		if(isset($_REQUEST['username']) && is_array($_REQUEST['username']) && !empty($_REQUEST['username']) && isset($_REQUEST['confirmuserdel'])) { 
			foreach($_REQUEST['username'] as $id) { 
				$affiliatesdb->remove_user($id);
				$visitsdb->remove_visits($id);
			}
			$reftracknotif->success('Selected users have been deleted');
			$_GET['action'] = 'noaction';
		}
		
	} break;
	case 'savechanges': { 
		
		$reftrackoptions_default = array(
			'cookie_length' => '30',
			'tracking_key' => 'ref',
		);
		
		$saveoptions = array();
		foreach($_POST['reftrackoptions'] as $key => $val) {
			$val = trim($val);
			if(empty($val)) { 
				$saveoptions[$key] = $reftrackoptions_default[$key];
			} else {
				$saveoptions[$key] = $val;
			}
		}
		update_option('reftrack_options', $saveoptions);
		$reftracknotif->success('Changes have been saved');
		
	} break;
}
