<?php

require_once('gbooksimport.php');
$import = new Gbooksimport();

$note = '';

if(isset($_POST['gbooks']) && !empty($_POST['gbooks'])) { 
	
	$ids = array();
	foreach($_POST['gbooks'] as $key => $val) { 
		$ids[] = $key;
	}
	$import->importBooks($ids);
	$note = $import->inserted.' items imported'; 
}

if(isset($_POST['gbisearchform']) && !empty($_POST['gbisearchform'])) { 
	$allbooks = $import->searchBooks($_POST['gbisearchform'], array('maxResults' => '40'));
} else { 
	$allbooks = array();
}

if(isset($_POST['gbi_apikey'])) { 
	$key = trim($_POST['gbi_apikey']);
	update_option('gbi_apikey', $key);
	$note = 'Changes Saved';
}

if(isset($_POST['mapfield'])) { 
	
	$clean = array();
	$clean['post_title'] = $_POST['post_title'];
	$clean['post_content'] = $_POST['post_content'];
	$clean['post_excerpt'] = $_POST['post_excerpt'];
	$clean['comment_status'] = $_POST['comment_status'];
	$clean['post_types'] = $_POST['post_types'];
	$clean['post_status'] = $_POST['post_status'];
	$clean['category'] = $_POST['category'];
	if(isset($_POST['customfields'])) { 
		$clean['customfields'] = array_unique($_POST['customfields']);
	}
	
	update_option('gbi_fields', $clean);
	$note = 'Changes Saved';
}

$book_fields = array('id','title','subtitle','authors','publisher','publishedDate','description','pageCount','categories','averageRating','smallThumbnail','mediumImage','largeImage','thumbnail','language','previewLink','infoLink','saleability','isEbook','viewability','epubacsTokenLink','pdfdownloadlink','webReaderLink');

$mappedfields = get_option('gbi_fields');
$key = get_option('gbi_apikey');

?>
<div class="wrap"><div id="icon-tools" class="icon32"></div>
<h2>Google Books Importer</h2>
	<div class="gbi_top">
		<?php if(!empty($note)) { ?><div class="updated"><p><strong><?php echo $note; ?></strong></p></div><?php } ?>
		<div id="gbi_mapfields">
			<a href="#" id="gbi_fmapbtn" class="button">Fields Mapping</a>
			<div class="gbi_fieldsmaparea">
				<form method="post">
				<table>
					<tr><td>Post Title</td><td><select name="post_title"><?php foreach($book_fields as $f) { ?>
						<option value="<?php echo $f; ?>" <?php if($mappedfields['post_title'] == $f) { echo "selected"; } ?>><?php echo $f; ?></option>
						<?php } ?></select></td></tr>
					<tr><td>Post Content</td><td><select name="post_content"><?php foreach($book_fields as $f) { ?>
						<option value="<?php echo $f; ?>" <?php if($mappedfields['post_content'] == $f) { echo "selected"; } ?>><?php echo $f; ?></option>
						<?php } ?></select></td></tr>
					<tr><td>Post Excerpt</td><td><select name="post_excerpt"><?php foreach($book_fields as $f) { ?>
						<option value="<?php echo $f; ?>" <?php if($mappedfields['post_excerpt'] == $f) { echo "selected"; } ?>><?php echo $f; ?></option>
						<?php } ?></select></td></tr>
					<tr><td>Post Status</td><td>
						<select name="post_status">
							<option value="publish" <?php if($mappedfields['post_status'] == 'publish') { echo "selected"; } ?>>publish</option>
							<option value="pending" <?php if($mappedfields['post_status'] == 'pending') { echo "selected"; } ?>>pending</option>
							<option value="draft" <?php if($mappedfields['post_status'] == 'draft') { echo "selected"; } ?>>draft</option>
						</select>
					</td></tr>
					<tr><td>Comment Status</td><td>
						<select name="comment_status">
							<option value="open" <?php if($mappedfields['comment_status'] == 'open') { echo "selected"; } ?>>open</option>
							<option value="closed" <?php if($mappedfields['comment_status'] == 'closed') { echo "selected"; } ?>>closed</option>
						</select>
					</td></tr>
					<tr><td>Post Type</td><td>
						<select name="post_types">
							<?php foreach(get_post_types() as $post_type) { ?>
								<option value="<?php echo $post_type; ?>" <?php if($mappedfields['post_types'] == $post_type) { echo "selected"; } ?>><?php echo $post_type; ?></option>
							<?php } ?>
						</select>
					</td></tr>
					<tr><td>Category</td><td><select name="category">
						<option value="0"> --- </option>
					<?php foreach($book_fields as $f) { ?>
						<option value="<?php echo $f; ?>" <?php if($mappedfields['category'] == $f) { echo "selected"; } ?>><?php echo $f; ?></option>
						<?php } ?>
					</select></td></tr>
					<tr><td> ------ </td></tr>
					<?php if(isset($mappedfields['customfields']) && !empty($mappedfields['customfields'])) { 
							foreach($mappedfields['customfields'] as $cfield) { ?>
								<tr><td>Custom Field</td><td><select name="customfields[]">
									<?php foreach($book_fields as $bf) { ?>
									<option value="<?php echo $bf; ?>" <?php if($bf == $cfield) { echo "selected"; } ?>><?php echo $bf; ?></option>
									<?php } ?>
								</select> <a href="#" class="cfieldremove">X</a></td></tr>
					<?php } } ?>
				</table>
				<a href="#" id="newcustomfield">+ New Custom Field</a><br />
				<input type="hidden" name="mapfield" value="1" /><br />
				<input type="submit" class="button" value="Save" />
				</form>
			</div>
			
		</div><!-- #gbi_top -->
		
		
		<div id="gbi_searchoptions">
			<a href="#" id="gbi_optionsbtn" class="button">Options</a>
			<div class="gbi_optionsarea"> 
				<form method="post">
					<p>API key: <input type="text" name="gbi_apikey" size="50" value="<?php if(isset($key)) { echo $key; } ?>" /></p>
					<input type="submit" class="button" value="Save" />
				</form>
			</div>
		</div><!-- #gbi_searchoptions -->
		
	</div>


	<div class="gbi_search_area">
		<form method="post">
		<input type="text" id="gbisearchform" name="gbisearchform" /> <input type="submit" class="button" value="Search Books" />
		</form>
	</div>

	<div class="gbi_results_area"> 
		<?php if(!empty($allbooks) && !is_string($allbooks)) { ?>
		<form method="post" action="">
		<div id="importbox"><input type="submit" value="Import" class="button" /></div>
		<div id="selectallbox"><input type="checkbox" /> Select All</div>
		<div class="clearfix"></div>
		<?php foreach($allbooks as $item) { ?>
			<div class="gbi_item">
				<a href="<?php echo $item['volumeInfo']['previewLink']; ?>" target="_blank" ><img src="<?php if(isset($item['volumeInfo']['imageLinks']['thumbnail'])) { echo $item['volumeInfo']['imageLinks']['thumbnail']; } else { echo plugins_url().'/google-books-importer/assets/noimage.png'; } ?>" height="140" width="120" /></a>
				<div class="gbi_item_meta">
				  <input type="checkbox" name="gbooks[<?php echo $item['id']; ?>]" class="gbi_itemcheckbox" /> <?php echo $item['volumeInfo']['title']; ?>
				</div>
			</div>
		<?php } ?> 
		<div class="clearfix"></div>
		<div id="importbox"><input type="submit" value="Import" class="button" /></div>
		</form>
		<?php } ?>
		<?php if(is_string($allbooks)) { echo $allbooks; } ?>
	</div>
</div>
