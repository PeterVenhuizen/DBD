$(document).ready(function() {
	CKEDITOR.timestamp='ABCD'; // Reload all .js and .css files
	CKEDITOR.config.width = '1098px';
	CKEDITOR.config.height = '500px';
	CKEDITOR.config.allowedContent = true;
	CKEDITOR.config.extraPlugins = 'youtube';
	CKEDITOR.config.youtube_width = '600';
	CKEDITOR.config.youtube_height = '450';
	CKEDITOR.config.youtube_related = false;
	CKEDITOR.config.forcePasteAsPlainText = true;

	// Menu 
	$('.count_children').show();
	$('.subpages').hide();
	$('.menuh2').click(function() {
		$(this).parent().find('ul').slideToggle();
		$(this).parent().find('span').toggle('200');
	});				
	
	// Show create new menu item
	$('.menuh2').click(function() {
		$('#subPageEdit').html("");
		$('#createPage').html("");							
		$('#editMenuItem').show();
	});
	
	// Show create new subpage
	$('.createNewSubpage').click(function() {
		$('#subPageEdit').html("");
		$('#editMenuItem').html("");
		$('#createPage').show();
	});	
	
	// Show create new menu item
	$('.createNewMenuPage').click(function() {
		$('#subPageEdit').html("");
		$('#createPage').html("");
		$('#editMenuItem').show();
	});	
	
	// AJAX: Edit menu item
	$('.menuh2').click(function() {
		var menu_id = $(this).attr('id');
		$.ajax({
			type: "POST",
			url: "actions/ajax.page_structure.php",
			data: "menuID="+menu_id,
			success: function(result) {
				$('#editMenuItem').html(result);
			}
		});
		
		$('#page_content').addClass('ckeditor');
		
	});
	
	// AJAX: Create new subpage
	$('.createNewSubpage').click(function() {
		var menu_id = $(this).attr('id');
		$.ajax({
			type: "POST",
			url: "actions/ajax.page_structure.php",
			data: "addID="+menu_id,
			success: function(result) {
				$('#createPage').html(result);
			}
		});
	});	
	
	// Check if field is empty
	jQuery.fn.extend({
		validate_field: function() {
			var text = $(this).val();
			if (text.length == 0) {
				$(this).removeClass('correct');
				$(this).addClass('error');
				return false;
			} else {
				$(this).removeClass('error');
				$(this).addClass('correct');
				return true;
			}
		}
	})	

	// Validate if fields are empty
	$('#subTitle, #pageTitle, #itemOrder').focusout(function() {
		$(this).validate_field();
	});
	
	// ON FOCUSOUT: Check if fields are empty
	$(document).on('focusout', '#pageTitle, #editMenuName, #itemOrder, #pageOrder', function() {
		$(this).validate_field();
	});

	// ON SUBMIT: Check edit menu item
	$(document).on('click', '#editMItem', function(e) {
		if (!$('#editMenuName, #itemOrder').validate_field()) {
			e.preventDefault();
		} else if (!$.isNumeric($('#itemOrder').val())) {
			$('#itemOrder').removeClass('correct');		
			$('#itemOrder').addClass('error');
			e.preventDefault();			
		}
	});
	
	// ON SUBMIT: Check create new subpage
	$(document).on('click', '#addPage', function(e) {
		if (!$('#pageTitle').validate_field()) {
			e.preventDefault();
		}
	});	

	$(document).on('click', '#submitSubpage', function(e) {
		if (!$('#subTitle, #pageOrder').validate_field()) {
			e.preventDefault();
		} else if (!$.isNumeric($('#pageOrder').val())) {
			$('#pageOrder').removeClass('correct');		
			$('#pageOrder').addClass('error');
			e.preventDefault();			
		}
	});

	
	// AJAX: Create new menu item
	$('.createNewMenuPage').click(function() {
		$.ajax({
			type: "POST",
			url: "actions/ajax.page_structure.php",
			data: "newMenuPage=1",
			success: function(result) {
				$('#editMenuItem').html(result);
			}
		});
	});
	
	// ON SUBMIT: Check new menu item
	$(document).on('click', '#createMenuItem', function(e) {
		if (!$('#editMenuName, #itemOrder').validate_field()) {
			e.preventDefault();
		} else if (!$.isNumeric($('#itemOrder').val())) {
			$('#itemOrder').removeClass('correct');		
			$('#itemOrder').addClass('error');
			e.preventDefault();			
		}
	});
	
});

