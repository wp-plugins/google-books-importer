jQuery(document).ready(function($) { 
	
	$("#gbi_fmapbtn").on('click', function(e) { 
		
		$(".gbi_fieldsmaparea").slideToggle('fast');
		e.preventDefault();
	});
	
	$("#gbi_optionsbtn").on('click', function(e) { 
		
		$(".gbi_optionsarea").slideToggle('fast');
		e.preventDefault();
	});
	
	$("#selectallbox input").change(function() { 
		var cboxes = $(".gbi_itemcheckbox");
		if($(this).is(":checked")) { 
			cboxes.attr('checked', 'checked');
		} else { 
			cboxes.removeAttr('checked');
		}
	});
	
	$("#newcustomfield").on('click', function(e) { 
		
		var newentry = '<tr><td>Custom Field</td><td><select name="customfields[]"><option value="id">id</option><option value="title">title</option><option value="subtitle">subtitle</option><option value="authors">authors</option><option value="publisher">publisher</option><option value="publishedDate">publishedDate</option><option value="description">description</option><option value="pageCount">pageCount</option><option value="categories">categories</option><option value="averageRating">averageRating</option><option value="smallThumbnail">smallThumbnail</option><option value="mediumImage">mediumImage</option><option value="largeImage">largeImage</option><option value="thumbnail">thumbnail</option><option value="language">language</option><option value="previewLink">previewLink</option><option value="infoLink">infoLink</option><option value="saleability">saleability</option><option value="isEbook">isEbook</option><option value="viewability">viewability</option><option value="epubacsTokenLink">epubacsTokenLink</option><option value="pdfdownloadlink">pdfdownloadlink</option><option value="webReaderLink">webReaderLink</option></select> <a href="#" class="cfieldremove">X</a></td></tr>';
		$('.gbi_fieldsmaparea table').append(newentry);
		e.preventDefault();
	});
	
	$(".gbi_fieldsmaparea").on('click', '.cfieldremove', function(e) { 
		$(this).parent().parent().remove();
		e.preventDefault();
	});

});
