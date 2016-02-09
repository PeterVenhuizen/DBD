$(document).ready(function () {  
    
    var clear_sidebar = function () {
        var side_bottom = $('#sidebar').height() + $('#sidebar').position().top,
            latest_top = $('#latest_news').position().top;
        if (latest_top < side_bottom && $(window).width() > 768) {
            $('#latest_news').css({"padding-top": side_bottom - latest_top});
        } else {
            $('#latest_news').css({'padding-top': 0});
        }
    };
    
    clear_sidebar();
    
    // Set height of sidebar
    $(window).resize(function () {
        clear_sidebar();
    });
    
});