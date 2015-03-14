<?php

class reftrack_visits { 
	
	private $wpdb;
	
	public function __construct() { 
		
		global $wpdb;
		$this->wpdb = $wpdb;
	}
	
	/*
	 * Select all visits
	 * 
	 * @return array - all columns
	 * */ 
	public function get_all_visits() { 
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_visits";
		$run = $this->wpdb->get_results($q, ARRAY_A);
		return $run;
	} 
	
	/*
	 * Select all visits per page
	 * 
	 * @return array - all columns
	 * */ 
	public function get_all_visits_perpage($limit = 10, $offset = 0, $orderby = 'ID', $order = 'DESC') { 
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_visits ORDER BY {$orderby} {$order} LIMIT {$limit} OFFSET {$offset}";
		$run = $this->wpdb->get_results($q, ARRAY_A);
		return $run;
	} 
	
	/*
	 * Select visit by ID 
	 * 
	 * @param int - ID 
	 * @return obj | null - database row
	 * */
	public function get_visit($visit_id) {
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_visits WHERE ID = %d";
		$run = $this->wpdb->get_row($this->wpdb->prepare($q, $visit_id));
		return $run;
	} 
	
	/*
	 * Select all visits by user ID 
	 * 
	 * @param int - user ID 
	 * @return array of objects or empty array
	 * */
	public function get_visits($user_id) { 
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_visits WHERE affiliates_id = %d";
		$run = $this->wpdb->get_results($this->wpdb->prepare($q, $user_id));
		return $run;
	} 
	
	
	/*
	 * Select all visits by column
	 * 
	 * @param mixed - column name
	 * @param mixed - row cell value
	 * @return array of result array or empty array
	 * */
	public function get_visits_by($key, $value) { 

		// in case of invalid key 
		if(!ctype_alnum(str_replace(array('-','_'), '', $key))) { return; }
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_visits WHERE ".$key." = %s";
		$run = $this->wpdb->get_results($this->wpdb->prepare($q, $value), ARRAY_A);
		return $run;
	} 
	
	/*
	 * Select all visits by column per page filtered
	 * 
	 * @return array of result array or empty array
	 * */
	public function get_visits_by_perpage($key, $value, $limit = 10, $offset = 0, $orderby = 'ID', $order = 'DESC') { 
		
		// in case of invalid key 
		if(!ctype_alnum(str_replace(array('-','_'), '', $key))) { return; }
		
		$q = "SELECT * FROM {$this->wpdb->prefix}reftrack_visits WHERE ".$key." = %s ORDER BY {$orderby} {$order} LIMIT {$limit} OFFSET {$offset}";
		$run = $this->wpdb->get_results($this->wpdb->prepare($q, $value), ARRAY_A);
		return $run;
	} 
	
	/*
	 * Remove all visits by user ID 
	 * 
	 * @param int - user ID 
	 * @return int - number of rows affected 
	 * */
	public function remove_visits($user_id) { 
		
		$q = "DELETE FROM {$this->wpdb->prefix}reftrack_visits WHERE affiliates_id = %d";
		$run = $this->wpdb->query($this->wpdb->prepare($q, $user_id));
		return $run;
		
	} 
	
	/*
	 * Insert visit 
	 * 
	 * @param int - user ID 
	 * @param string - headers 
	 * @param string - IP address
	 * @param string - url 
	 * */
	public function insert_visit($user_id, $headers, $ip, $url) { 
		
		$q = "INSERT INTO {$this->wpdb->prefix}reftrack_visits VALUES ('', %d, %s, %s, %s, NOW())";
		$run = $this->wpdb->query($this->wpdb->prepare($q, $user_id, $headers, $ip, $url));
		return $run;
		
	}
	
	/*
	 * Get all visits per page formatted for displaying in admin table 
	 * 
	 * @return array - selected columns
	 * */
	public function get_visits_tabledata($limit = 10, $offset = 0, $orderby = 'ID', $order = 'DESC') { 
		
		global $affiliatesdb;
		
		$visits = $this->get_all_visits_perpage($limit, $offset, $orderby, $order);
		$table = array();
		foreach($visits as $row) { 
			$table[] = array(
				'ID' => $row['ID'],
				'IP' => $row['IP'],
				'username' => $affiliatesdb->get_user_by_id($row['affiliates_id'])->username,
				'visited_url' => $row['current_url'],
				'date' => date('d M Y, h:i', strtotime($row['date'])),
			);
		}

		return $table;
	}
	
	
	/*
	 * Get all visits per page filtered for displaying in admin table 
	 * 
	 * @return array - selected columns
	 * */
	public function get_visits_tabledata_by_filter($filter_key, $filter_value, $limit = 10, $offset = 0, $orderby = 'ID', $order = 'DESC') { 
		
		global $affiliatesdb;
		
		$visits = $this->get_visits_by_perpage($filter_key, $filter_value, $limit, $offset, $orderby, $order);
		$table = array();
		foreach($visits as $row) { 
			$table[] = array(
				'ID' => $row['ID'],
				'IP' => $row['IP'],
				'username' => $affiliatesdb->get_user_by_id($row['affiliates_id'])->username,
				'visited_url' => $row['current_url'],
				'date' => date('d M Y, h:i', strtotime($row['date'])),
			);
		}

		return $table;
	}
	
}


