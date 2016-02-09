$(document).ready(function() {
	
	// Change banner
	$('.submit_banner').click(function(e) {
		e.preventDefault();
		var id = $(this).attr('id'),
			title = $(this).parent().find('.banner_title').val(),
			desc = $(this).parent().find('.banner_desc').text(),
			url = $(this).parent().find('.banner_url').val(),
			url_text = $(this).parent().find('.banner_url_text').val(),
			img = $(this).parent().find('.banner_img option:selected').val();
		//if (!$(this).parent().find('.banner_title').is_empty() && !$(this).parent().find('.banner_desc').is_empty() && !$(this).parent().find('.banner_url').is_empty() && !$(this).parent().find('.banner_url_text').is_empty()) {
		    $.post('actions/ajax.changeBanner.php', { id: id, title: title, desc: desc, url: url, url_text: url_text, img: img }).done(function () {
		        location.reload();
		    });		
		//}
	});
	
	// Delete banner
    $(document).on('click', '.banner_small', function () {
        var filename = $(this).children('img').attr('id');
        if (confirm("Weet je zeker dat je deze foto wilt verwijderen?")) {
            $.post('actions/ajax.deleteBanner.php', {filename: filename}).done(function () {
                location.reload();
            });
        }
    });
});
