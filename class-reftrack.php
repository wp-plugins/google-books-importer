<?php

class reftrack { 
	
	public $options;
	
	public function __construct() { 

		$this->options = get_option('reftrack_options');
		
	}
	
	public function install() { 

		global $wpdb;
		
		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}reftrack_affiliates (ID MEDIUMINT NOT NULL AUTO_INCREMENT, username VARCHAR(150) NOT NULL, tracking_value VARCHAR(300) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY (ID)) CHARACTER SET utf8 COLLATE utf8_general_ci";
		$wpdb->query($sql);
		
		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}reftrack_visits (ID MEDIUMINT NOT NULL AUTO_INCREMENT, affiliates_id MEDIUMINT NOT NULL, request_headers TEXT NOT NULL, IP varchar(80) not null, current_url TEXT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY (ID)) CHARACTER SET utf8 COLLATE utf8_general_ci";
		$wpdb->query($sql);
		
	}
	
	public function request_init() { 
		
		global $visitsdb, $affiliatesdb;
		
		// exclude ajax cals 
		if(defined('DOING_AJAX') && DOING_AJAX) { return; } 
		// exclude admin panel
		if(is_admin()) { return; }
		
		if(isset($_COOKIE['reftrack_c'])) { 
			$cookievals = $this->reftrack_getcookie($_COOKIE['reftrack_c']);
			$cuser = $affiliatesdb->get_user_by_trackingvalue($cookievals[1]);
			if(!is_null($cuser)) { 
				$this->logvisit($cuser->ID);
			}
			return;
		}
		
		if(!isset($_GET[$this->options['tracking_key']])) { 
			return;
		}
		
		$tracking_value = trim($_GET[$this->options['tracking_key']]);
		$cuser = $affiliatesdb->get_user_by_trackingvalue($tracking_value);
		
		if(is_null($cuser)) { 
			return;
		}

		$this->reftrack_setcookie($tracking_value);
		$this->logvisit($cuser->ID);
		
	}
	
	public function reftrack_setcookie($tracking_value) { 
		
		$signature = $this->options['cookiesign'];
		$cookieval = base64_encode($this->options['tracking_key'].'-'.$tracking_value.'-'.$signature);
		setcookie('reftrack_c', $cookieval, time() + $this->options['cookie_length'] * 60 * 60 * 24, SITECOOKIEPATH, COOKIE_DOMAIN);
		
	}
	
	/*
	 * Get current user cookie info 
	 * 
	 * @param string - encoded cookie value
	 * @return array | bool - tracking key, tracking value and signature | false
	 * */
	public function reftrack_getcookie($cookie) { 
	
		$cookie = explode('-', base64_decode($cookie)); 
		if(count($cookie) == 3) { 
			return $cookie;
		} else { 
			return false;
		}
		
	}
	
	/*
	 * Store page request in database 
	 * 
	 * @param int - affiliate ID
	 * @return void 
	 * */
	public function logvisit($id) { 
		
		global $visitsdb;
		
		// available data 
		$request_headers = serialize(getallheaders());
		$request_ip = $_SERVER['REMOTE_ADDR'];
		$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
		$request_uri = $protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$visitsdb->insert_visit($id, $request_headers, $request_ip, $request_uri);
		
	}
	
}
