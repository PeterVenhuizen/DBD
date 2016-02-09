/* Set page header based on selected page */
function set_page_header() {
	var url = window.location.href;
	var url_array = url.split('/');
	var page = url_array[url_array.length - 1];
    if (page.indexOf('?') !== -1) {
        page = page.split('?');
        page = page[page.length - 2];
        console.log(page);
    }
	if (page.length == 0) { page = '/cms_next/'; }
	var header = $('#cms_nav option[value="'+page+'"]').text();
	$('#cms_nav option[value="'+page+'"]').prop('selected', true);
    $('#h1_selected').text(header);
}

$(document).ready(function() {
    set_page_header();

    /* Go to different page */
    $('#cms_nav').change(function() {
        var url = $('#cms_nav option:selected').val();
        window.location.href = url;
    });
    
    /* Display help */
    $('.img_get_help').click(function() {
        $(this).nextAll('.help').first().toggle(); 
    });
});
