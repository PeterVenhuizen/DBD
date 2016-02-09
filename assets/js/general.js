$(document).ready(function () {

	// Toggle menu visability
	$(document).on('click', '#img_menu', function () {
		$('#menu').toggle();
	});
	
	// Submenu expand
	$(document).on('click mouseover', '.ul_expand', function (e) {
		e.preventDefault();
		$(this).parent().children('ul').toggle();
        $(this).parent().siblings().children('ul').hide();
		
		var text = $(this).siblings('.expand').text();
        $('.ul_expand').not(this).siblings('.expand').text('+');        
        $(this).siblings('.expand').text(text === '+' ? '-' : '+');
	});
	
	//Internal back to top links
	$(document).on('click', '.to_top', function (e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 250);
	});
    
    // Change agenda 
    $(document).on('change', '.select_type', function () {
        var selection = $('.select_type').val();
        $.post('assets/ajax/loadAgenda.php', { agenda : selection } ).done(function (data) {
            $('#agenda').find('tr:gt(0)').remove();
            $('#agenda').append(data);
        });
    });
    
    // Change archive posts
    $(document).on('change', '#select_news', function () {
        var category = $(this).val();
        $.post('assets/ajax/loadArchive.php', { category : category } ).done(function (result) {
            $('#archive_posts').html(result);
        });
    });
    
});

/* AUTO YOUTUBE RESIZE */
// By Chris Coyier & tweaked by Mathias Bynens

$(function () {

	// Find all YouTube videos and images
	var $allVideos = $("iframe[src^='//www.youtube.com'], article.news img"),

		// The element that is fluid width
		$fluidEl = $("article");

	// Figure out and save aspect ratio for each video
	$allVideos.each(function () {

		var height = $(this).height(),
            width = $(this).width();

		$(this)
			.data('aspectRatio', height / width)

			// and remove the hard coded width/height
			.removeAttr('height')
			.removeAttr('width');

	});

	// When the window is resized
	// (You'll probably want to debounce this)
	$(window).resize(function () {

		var newWidth = $fluidEl.width();

		// Resize all videos according to their own aspect ratio
		$allVideos.each(function () {

			var $el = $(this);
			$el
				.width(newWidth)
				.height(newWidth * $el.data('aspectRatio'));

		});

	// Kick off one resize to fix all videos on page load
	}).resize();

});