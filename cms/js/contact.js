$(document).ready(function() {
    
    /* Toggle form */
    $('#btn_add').click(function() {
        $('#form_add_contact').toggle();
    });
    
    /* Validate form */
    $('#activity, #description').focusout(function() { $(this).is_empty(); });
    $('#date').focusout(function() {  $(this).is_valid_date(); });
    $('#submit_activity').click(function(e) {
        if ($('#activity').is_empty() || !$('#date').is_valid_date() || $('#description').is_empty()) {
            e.preventDefault();
        }
    });
    
    $('#edit_activity, #edit_description').focusout(function() { $(this).is_empty(); });
    $('#edit_date').focusout(function() {  $(this).is_valid_date(); });
    $('#submit_edit_activity').click(function(e) {
        if ($('#edit_activity').is_empty() || !$('#edit_date').is_valid_date() || $('#edit_description').is_empty()) {
            e.preventDefault();
        }
    });
});
