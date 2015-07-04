<?php

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Reftrack_Visits_List_Table extends WP_List_Table { 
	
	public $visits;
	
	public function __construct() {
		 
		parent::__construct(array(
			'singular' => 'visit',
			'plural' => 'visits',
		));

	}
	
	public function get_columns() { 

		$cols = array(
			'IP' => 'IP',
			'username' => 'Username',
			'visited_url' => 'Visited Link',
			'date' => 'Time',
		);
		return $cols;
		
	}
	
	public function prepare_items() {
		
		global $visitsdb;
		
		$orderby = (isset($_GET['orderby']) && ctype_alnum($_GET['orderby'])) ? $_GET['orderby'] : 'ID';
		$order = (isset($_GET['order']) && is_string($_GET['order'])) ? $_GET['order'] : 'DESC';
		
		$per_page = 20;
		$current_page = $this->get_pagenum();
		
		if(isset($_GET['user']) && is_numeric($_GET['user'])) { 
			$this->visits = $visitsdb->get_visits_by('affiliates_id', $_GET['user']);
			$found_data = $visitsdb->get_visits_tabledata_by_filter('affiliates_id', $_GET['user'], $per_page, $per_page * ($current_page - 1), $orderby, $order);
		} else { 
			$this->visits = $visitsdb->get_all_visits();
			$found_data = $visitsdb->get_visits_tabledata($per_page, $per_page * ($current_page - 1), $orderby, $order);
		}
		
		$this->items = $found_data;
		$total_items = count($this->visits);

		$columns = $this->get_columns();
		$hidden = array();	
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
		 ));
		
	}
	
	public function column_default($item, $column_name) { 
		
	  switch( $column_name ) { 
		case 'IP':
		case 'username':
		case 'visited_url':
		case 'date':
		  return $item[$column_name];
		default:
		  return var_dump($item);
	  }
	  
	}
	
	public function get_sortable_columns() {
		
	  $sortable_columns = array(
		'date'  => array('date', false),
	  );
	  return $sortable_columns;
	  
	}
	
}

