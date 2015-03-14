<?php

/*
 * Admin pagination function 
 * 
 * @param int - total amount of items to show 
 * @param int - items per page 
 * @return string - pagination code
 * */
function reftrack_pagination($totalitems, $perpage = 10) { 

	$pages = ceil($totalitems / $perpage); 
	if($pages <= 1) { 
		return;
	}

	// current page 
	$current = isset($_GET['paged']) ? $_GET['paged'] : 1;
	
	$pagination = '';
	$pagination .= '<ul class="reftrack-pagination">';
	
	if(1 != $current) { 
		$prev = $current - 1;
		$pagination .= '<li><a href="'.get_pagenum_link($prev).'">&laquo;</a></li>';
	}
	

	for($x = 1; $x <= $pages; $x++) {
		$activeclass = ($current == $x) ? 'reftrack-activepage' : '';
		if($x != 1 && $x != $pages) { 
			 if($x == $current || $x == $current - 1 || $x == $current + 1) { 
				$pagination .= '<li class="'.$activeclass.'"><a href="'.get_pagenum_link($x).'">'.$x.'</a></li>';
			}
		} else { 
			$pagination .= '<li class="'.$activeclass.'"><a href="'.get_pagenum_link($x).'">'.$x.'</a></li>';
		}
	}
	
	if($pages != $current) { 
		$next = $current + 1;
		$pagination .= '<li><a href="'.get_pagenum_link($next).'">&raquo;</a></li>';
	}
	
	$pagination .= '</ul>';
	return $pagination;
	
}

