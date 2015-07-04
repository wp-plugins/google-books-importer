<?php

if(!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Reftrack_Users_List_Table extends WP_List_Table { 
	
	public $reflist;
	
	public function __construct() {
		 
		parent::__construct(array(
			'singular' => 'referral',
			'plural' => 'reerrals',
		));

	}
	
	public function get_columns() { 
		
		$cols = array(
			'cb' => 'cb',
			'username' => 'Username',
			'tracking_value' => 'Tracking Code',
			'referral_link' => 'Referral Link',
		);
		return $cols;
		
	}
	
	public function column_default($item, $column_name) { 
	  switch( $column_name ) { 
		case 'username':
		case 'tracking_value':
		case 'referral_link':
		  return $item[$column_name];
		default:
		  return var_dump($item);
	  }
	}
	
	public function column_username($item) { 

		$actions = array(
			'view' => '<a href="'.admin_url('admin.php?page=reftrack-visits&user='.$item['ID']).'">Visited Pages</a>'
		);
		
		return '<a href="'.admin_url('admin.php?page=reftrack-visits&user='.$item['ID']).'"><strong>'.$item['username'].'</strong></a>'.$this->row_actions($actions);
	}
	
	public function column_referral_link($item) { 
		
		return '<input type="text" class="referral_link_value" onfocus="this.select();" readonly value="'.$item['referral_link'].'">';
	}
	
	public function column_cb($item) { 
		
		 return '<input type="checkbox" name="username[]" value="'.$item['ID'].'" />';
	}
	
	public function prepare_items() { 
		
		global $affiliatesdb;

		$per_page = 10;
		$current_page = $this->get_pagenum();
		$this->reflist = $affiliatesdb->get_all_users();
		$this->items = $affiliatesdb->get_users_tabledata($per_page, $per_page * ($current_page - 1));
		$total_items = count($this->reflist);
		
		$columns = $this->get_columns();
		$hidden = array();	
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'per_page'    => $per_page,
		 ));
		
	}
	
	function get_bulk_actions() {
	  $actions = array(
		'delete'    => 'Delete'
	  );
	  return $actions;
	}
	
	
}

