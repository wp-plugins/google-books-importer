<?php

class reftrack_notifcations { 
	
	public $message = 'Example Message';
	public $has_error = false;
	
	public $is_error = false;
	public $is_success = false;
	public $is_note = false;
	
	public $msgclass = 'notice';
	
	public function error($message) { 
		
		$this->is_error = true;
		$this->has_error = true;
		$this->msgclass = 'error';
		$this->message = $message;

	}
	
	public function success($message) { 
		
		$this->is_success = true;
		$this->message = $message;
		$this->msgclass = 'updated';
		
	}
	
	public function displaymsg() { 
		
		if($this->is_error || $this->is_success || $this->is_note) { 
			echo '<div class="'.$this->msgclass.' reftrack-note">';
			echo '<p><strong>'.$this->message.'</strong></p>';
			echo '</div>';
		}
		
	}
	
}
