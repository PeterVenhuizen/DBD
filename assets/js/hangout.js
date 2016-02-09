// Live search function
searchMembers = function(e) {
    $(e).keyup(function() {
        if ($('#private_search').val().length != 0) {
            var search_data = $('#private_search').val();
            $.ajax({
                type: "POST",
                url: "assets/ajax/searchMembers.php",
                data: "search_data="+search_data,
                success: function(result) {
                    $('#private_search_result').show();
                    $('#private_search_result').html(result);
                }
            });
        } else {
            $('#private_search_result').hide();
            $('#selected_person_info').hide();
        }
    });
}	

$(document).ready(function() { 
	
    //Live search members
	$('#private_search_result').hide();
    
    //Get member information
	$('#selected_person_info').hide();
	$(document).on('click', '.search_person_info', function() {
		var person_id = $(this).attr('class').split(' ')[0];
		$.ajax({
			type: "POST",
			url: "assets/ajax/loadPersonInfo.php",
			data: "person_id="+person_id,
			success: function(result) {
				$('#selected_person_info').show();
				$('#selected_person_info').html(result);
			}
		});					
	});
    
    //Events - toggle visibility
	$(document).on('click', '.activity', function() {
		$(this).children('.activity_body').toggle();
	});
    
	//Events - Subscribe
	$(document).on('click', '.btn_activity_subscribe', function() {
		var sub_id = $(this).val();
		var act_id = $(this).attr('id');
        $.post('assets/ajax/eventPresence.php', { sub_id: sub_id, activity_id: act_id}).done(function () {
            location.reload();
        });
	});
		
	//Events - Unsubscribe
	$(document).on('click', '.btn_activity_unsubscribe', function() {
		var unsub_id = $(this).val();
		var act_id = $(this).attr('id');
        $.post('assets/ajax/eventPresence.php', { unsub_id: unsub_id, activity_id: act_id}).done(function () {
            location.reload();
        });
	});	 
    
    // Downloads
	$('#list_downloads').hide();
	$('#member_downloads h2').click(function() {
		$('#list_downloads').toggle();
	}); 
    
    // User info
	//Update user info
	$('#form_change_user_info').hide();
	$('#user_info_explanation').hide();
	
	$(document).on('click', '#user_info h2', function() {
		$('#form_change_user_info').toggle();
		$('#user_info_explanation').toggle();					
	});

	$('#user_first_name, #user_last_name, #user_street, #user_house_number').focusout(function() {
		$(this).isEmpty();
	});
	
	$('#user_birth_date').focusout(function() {
		$(this).is_valid_date();
	});
	
	$('#user_email, #alt_email').focusout(function() {
		$(this).isValidEmail();
	});

	$('#user_zip_code').focusout(function() {
		$(this).is_valid_zip_code();
	});

	$(document).on('click', '#submit_user_info_form', function(e) {
		if ($('#user_first_name').isEmpty() || $('#user_last_name').isEmpty() || !$('#user_birth_date').is_valid_date() || !$('#user_email').isValidEmail()) {
			e.preventDefault();					
		}
	});	
    
    
    // CHANGE PASSWORD
	$('#form_change_user_password').hide();
	$('#change_pw_error').hide();		
	$(document).on('click', '#change_password h2', function() {
		$('#form_change_user_password').toggle();					
	});	
	// ON FOCUSOUT: Check if password is correct
	var passwordIsCorrect = false;
	$(document).on('focusout', '#current_pw', function() {
		var id = $(this).parent().find('input#user_id').val();
		var password = $(this).val();
		$.ajax({
			type: "POST",
			url: "assets/ajax/passwordValidation.php",
			data: "check_password="+password+"&user_id="+id,
			success: function(result) {
				if (result != 'true') {
					$('#change_pw_error').show();
					$('#change_pw_error').html(result);
					passwordIsCorrect = false;
				} else {
					$('#change_pw_error').hide();
					passwordIsCorrect = true;
				}
			}						
		});				
	});	
	//Check if passwords are similar
	$('#repeat_pw').focusout(function() {
		var pw1 = $('#new_pw').val();
		var pw2 = $(this).val()
		if (pw1 != pw2) {
			$('#change_pw_error').show();
			$('#change_pw_error').html('Wachtwoorden komen niet overeen, probeer opnieuw.');
		} else {
			if ($('#change_pw_error').html() == 'Wachtwoorden komen niet overeen, probeer opnieuw.') {
				$('#change_pw_error').hide();
			}
		}
	});	
	//ON SUBMIT: Check if all fields are correct
	$(document).on('click', '#change_pw_submit', function(e) {
		if (!$('#new_pw').is_not_empty() || !$('#repeat_pw').is_not_empty() || $('#new_pw').val() != $('#repeat_pw').val() || !passwordIsCorrect) {
			e.preventDefault()
			$('#change_pw_error').show();
			$('#change_pw_error').html('Wachtwoord wijzigen mislukt, probeer opnieuw.');
		} else {
			$('#change_pw_error').hide();
			alert('Je wachtwoord is succesvol gewijzigd!');
		}
	});	    
    
});