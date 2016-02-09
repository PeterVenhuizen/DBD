/* IMAGE SLIDER ON HOME PAGE */
function slideShow(speed) {
    
	//Set opacity of all image to 0.0
	$('.slideshow li').css({opacity: 0.0});
	//Set opacity of first image to 1.0
	$('.slideshow li:first').css({opacity: 1.0}).addClass('show');
	
	//Get the title and description of the shown image
	$('#banner_box h1').html($('.slideshow li.show').children('.title').html());
	$('#banner_box p').html($('.slideshow li.show').children('.description').html());


	//Get and set the link
	$('#banner_box > a').attr('href', $('.slideshow li.show').children('a').attr('href'));
	$('#banner_box > a').html($('.slideshow li.show').children('a').attr('alt'));
	
	//Call gallery function and run slideshow
	var timer = setInterval('galleryImgSlider()',speed);
	
	//Pause the slideshow on mouse over
	$('.slideshow').hover(function() {
		clearInterval(timer);
		}, function() {
			timer = setInterval('galleryImgSlider()',speed);
		}
	);
}

function galleryImgSlider() {
	//Set first image, if no image has the show class
	var current = ($('.slideshow li.show')? $('.slideshow li.show') : $('.slideshow li:first'));
    var dot = ($('#banner_dots .filled')? $('#banner_dots .filled') : $('#banner_dots li:first'));
	
	if(current.queue('fx').length === 0) {
		//Go back to the first image, if the last image has been reached
		var next = ((current.next().length) ? ((current.next().attr('id') === 'banner_box') ? $('.slideshow li:first') : current.next()) : $('.slideshow li:first'));
		var next_dot = ((dot.next().length) ? ((dot.next().attr('id') === 'banner_box') ? $('#banner_dots li:first') : dot.next()) : $('#banner_dots li:first'));
        
        dot.removeClass('filled');
        next_dot.addClass('filled');
        
		//Set the fade in effect for the next image
		next.css({opacity: 0.0}).addClass('show').animate({opacity: 1.0}, 1000);
		
		//Hide current image
		current.animate({opacity: 0.0}, 1000).removeClass('show');
		
		//Delay the refreshing of the title and description
		setTimeout(function() {
			//Set the new title and description
			$('#banner_box h1').html(next.children('.title').html());
			$('#banner_box p').html(next.children('.description').html());

			//Set the new link
			$('#banner_box > a').attr('href', next.children('a').attr('href'));
			$('#banner_box > a').html(next.children('a').attr('alt'));
		}, 300);
	}
    
}

$(document).ready(function () {
    slideShow(5000);
});