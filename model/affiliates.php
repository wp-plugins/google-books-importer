<?php

class reftrack_affiliates { 
	
	private $wpdb;
	private $reftrackoptions;
	
	public function __construct() { 
		
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->reftrackoptions = get_option('reftrack_options');
	}
	
	/*
	 * Get all users 
	 * 
	 * @return array of objects or empty array
	 * */ 
	public function get_all_users() { 
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_affiliates";
		$run = $this->wpdb->get_results($q, ARRAY_A);
		return $run;
	}
	
	/*
	 * Get users 
	 * 
	 * @return array of objects or empty array
	 * */ 
	public function get_users($limit = 10, $offset = 0) { 
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_affiliates LIMIT {$limit} OFFSET {$offset}";
		$run = $this->wpdb->get_results($q, ARRAY_A);
		return $run;
	}
	
	/*
	 * Get user by ID 
	 * 
	 * @param int - user ID 
	 * @return obj | null - database row
	 * */	
	public function get_user_by_id($val) {  
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_affiliates WHERE ID = %d";
		$run = $this->wpdb->get_row($this->wpdb->prepare($q, $val));
		return $run;
	}
	
	/* Get user by username 
	 * 
	 * @param string - username
	 * @return obj | null - database row 
	 * */
	 public function get_user_by_username($val) { 

		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_affiliates WHERE username = %s";
		$run = $this->wpdb->get_row($this->wpdb->prepare($q, $val));
		return $run; 
	 }
	 
	
	/*
	 * Get user by tracking value 
	 * 
	 * @param int - tracking value
	 * @return obj | null - database row
	 * */	
	public function get_user_by_trackingvalue($val) {  
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_affiliates WHERE tracking_value = %s";
		$run = $this->wpdb->get_row($this->wpdb->prepare($q, $val));
		return $run;
	}
	
	
	/*
	 * Remove User 
	 *
	 * @param int - user ID 
	 * @return int - 0 | 1
	 * */
	public function remove_user($id) { 
		
		$q = "DELETE FROM {$this->wpdb->prefix}reftrack_affiliates WHERE ID = %d LIMIT 1";
		$run = $this->wpdb->query($this->wpdb->prepare($q, $id));
		return $run;
	} 
	
	/*
	 * Update tracking value 
	 * 
	 * @param int - user ID 
	 * @param string - new tracking value 
	 * @return int - number of rows affected 
	 * */
	public function update_tracking_value($id, $val) { 
		
		$q = "UPDATE {$this->wpdb->prefix}reftrack_affiliates SET tracking_value = %s WHERE ID = %d LIMIT 1";
		$run = $this->wpdb->query($this->wpdb->prepare($q, $val, $id));
		return $run;
		
	}
	
	/*
	 * Insert new user 
	 * 
	 * @param string - username
	 * @param string - tracking code 
	 * @return int - 0 | 1
	 * */
	 public function insert_user($username, $code) { 
		 
		 $q = "INSERT INTO {$this->wpdb->prefix}reftrack_affiliates VALUES ('', %s, %s, NOW())";
		 $run = $this->wpdb->query($this->wpdb->prepare($q, $username, $code));
		 return $run;
		 
	 }
	
	/* Get users per page for admin table 
	 * 
	 * @return array 
	 * */
	 public function get_users_tabledata($limit = 10, $offset = 0) { 
		 
		$users = $this->get_users($limit, $offset);
		$table = array(); 
		foreach($users as $row) { 
			$table[] = array(
				'ID' => $row['ID'],
				'username' => $row['username'],
				'tracking_value' => $row['tracking_value'],
				'referral_link' => site_url('?'.$this->reftrackoptions['tracking_key'].'='.$row['tracking_value']),
			);
		}

		return $table;
		 
	 }
	
}


