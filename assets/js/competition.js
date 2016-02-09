$(document).ready(function () {
    
	//On page load, load the competition for
    //the first team in the select list
    
    var first_team = $('#select_team option:first-child').val();
    
	$.ajax({
		type: 'POST',
		url: 'assets/ajax/loadCompetition.php',
		data: 'teamId=' + first_team,
		success: function (result) {
            $('#ajax_competition_content').html(result);
		}
	});
    
    $('#select_team').change(function () {
        var teamId = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'assets/ajax/loadCompetition.php',
            data: 'teamId=' + teamId,
            success: function (result) {
                $('#ajax_competition_content').html(result);
            }
        });
    });
});