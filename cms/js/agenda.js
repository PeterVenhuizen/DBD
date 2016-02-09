$(document).ready(function() {
    
    /* Toggle form */
    $('#btn_add').click(function() { $('#form_add_event').toggle(); });
    
    /* Enable/disable end date */
    var setEndDate = function() {
        if ($('#use_end_date').is(':checked')) { $('#end_date').prop('disabled', false); }  
        else { $('#end_date').prop('disabled', 'disabled'); }
    };
    $(setEndDate);
    $('#use_end_date').change(setEndDate);
    
    /* Enable/disable time */
    var setStartTime = function() {
        if ($('#use_start_time').is(':checked')) { $('#time').prop('disabled', false); }  
        else { $('#time').prop('disabled', 'disabled'); }
    };
    $(setStartTime);
    $('#use_start_time').change(setStartTime);
    
    /* Validation */
    $('#activity, #location').focusout(function() { $(this).is_empty(); });
    $('#start_date, #end_date').focusout(function() { $(this).is_valid_date(); });
    $('#time').focusout(function() { $(this).is_valid_time(); });
    $('#submit_event').click(function(e) {
        if ($('#activity').is_empty() || $('#location').is_empty()) { e.preventDefault(); }
        else if (!$('#start_date').is_valid_date() || ($('#use_end_date').is(':checked') && !$('#end_date').is_valid_date())) { e.preventDefault(); }
        else if ($('#use_start_time').is(':checked') && !$('#time').is_valid_time()) { e.preventDefault(); }
    });
    
});