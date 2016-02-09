/* DIASHOW FUNCTIONS */
function diaShow(speed) {
	//Set opacity of all image to 0.0
	$('photo-container').css({opacity: 0.0});
	//Set opacity of first image to 1.0
	$('photo-container:nth-child(2)').css({opacity: 1.0}).addClass('show');

	//Call gallery function and run slideshow
	//var timer = setInterval('nextDia()', speed);

	//Pause and play diashow
	$('#pause_play').click(function() {
		var pauseplay = $(this).attr('class');
		if (pauseplay == 'pause') {
			timer = setInterval('nextDia()', speed);
			$(this).removeClass('pause');
			$(this).addClass('play');
			$(this).css('background-position', '0 0');
		} else {
			clearInterval(timer);
			$(this).removeClass('play');
			$(this).addClass('pause');
			$(this).css('background-position','40px 0');
		}
	});
	
	// Reset interval when a thumbnail is clicked
	$('.thumb').click(function() {
		var pauseplay = $(this).attr('class');
		if (pauseplay == 'play') {
			clearInterval(timer);
			timer = setInterval('nextDia()', speed);
		}
	});
}

function nextDia() {
	//Set first image, if no image has the show class
	var current = ($('photo-container.show')? $('photo-container.show') : $('photo-container:nth-child(2)'));

	//Go back to the first image, if the last image has been reached
	var next = ((current.next().length) ? ((current.next().hasClass('end')) ? $('photo-container:nth-child(2)') : current.next()) : $('photo-container:nth-child(2)'));

	//Set the fade in effect for the next image
	next.css({opacity: 0.0}).addClass('show').animate({opacity: 1.0}, 1000);

	//Hide current image
	current.animate({opacity: 0.0}, 1000).removeClass('show');						
}

function previousDia() {
	//Set first image, if no image has the show class
	var current = ($('photo-container.show')? $('photo-container.show') : $('photo-container:nth-child(2)'));

	//Go back to the first image, if the last image has been reached
	var prev = ((current.prev().length) ? ((current.prev().hasClass('start')) ? $('photo-container:nth-last-child(2)') : current.prev()) : $('photo-container:nth-last-child(2)'));

	//Set the fade in effect for the next image
	prev.css({opacity: 0.0}).addClass('show').animate({opacity: 1.0}, 1000);

	//Hide current image
	current.animate({opacity: 0.0}, 1000).removeClass('show');				
}


$(document).ready(function () {
    
    /* ALBUM PREVIEW */
    // Load photo album thumbnails from latest year
    var last_year = $('.year:first').html();
    $.post('assets/ajax/loadAlbumPreview.php', { album_year : last_year } ).done(function (data) {
         $('#photo-preview-section').html(data);	
    });

	// Load album previews for clicked years
	$('.year').click(function() {
		var year = $(this).html();
        $.post('assets/ajax/loadAlbumPreview.php', { album_year : year } ).done(function (data) {
             $('#photo-preview-section').html(data);
        });
	});	
    
    
    /* SHOW ALBUM */
    // Album controls

    if ($(window).width() > 1024) {
        diaShow(2500);	

        $(document).on('click', 'close-dia-show', function() {
            $('#pause_play').removeClass('play');
            $('#pause_play').addClass('pause');				
            $('#dia_show').empty();
            clearInterval(timer);									
        });	

        //Previous 
        $(document).on('click', '#play_previous', function() {
            previousDia();
        });

        //Next
        $(document).on('click', '#play_next', function() {
            nextDia();
        });	

        // Jump to thumbnail
        $(document).on('click', '.thumb', function() {
            var link = $(this).attr('src');
            $('photo-container').removeClass('show');
            $('photo-container').css('opacity', '0');
            $(".photo[src='" + link + "']").parent().addClass('show');
            $(".photo[src='" + link + "']").parent().css('opacity', '1');
        });   

        // Download image
        $(document).on('click', '#save_image', function() {
            var album_name = $('#album_name').attr('name');
            var src = $('photo-container.show').find('img').attr('src');
            window.location.href = 'assets/ajax/downloadImage.php?file=' + src;
        }); 
    }
    
});