function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}

var unique = false;
function emailIsUnique(email) {
    $.post('assets/ajax/registrationValidation.php', { check_email: email }).done( function (result) {
        if (result !== 'true') {
            $('#register_error').show();
            $('#register_error').html(result);            
        } else {
            $('#register_error').hide();
            unique = true;
        }
    });
}

$(document).ready(function () {
	/* REGISTER */
	$('#register_error').hide();

	//Check if passwords are similar
	$('#register_confirm_password').focusout(function () {
		var pw1 = $('#register_password').val(),
		    pw2 = $(this).val();
		if (pw1 !== pw2) {
			$('#register_error').show();
			$('#register_error').html('Wachtwoorden komen niet overeen, probeer opnieuw.');
		} else {
			if ($('#register_error').html() === 'Wachtwoorden komen niet overeen, probeer opnieuw.') {
				$('#register_error').hide();
			}
		}
	});
	// ON FOCUSOUT: Check if email exists
	$(document).on('focusout', '#register_email', function (e) {
		var email = $(this).val();
		if (!isValidEmailAddress(email)) {
			$('#register_error').show();
			$('#register_error').html('Vul een geldig email-adres in.');
		} else {
            emailIsUnique(email);   
        }
	});
	
	$('#register_submit').hide();
	$(document).on('click', '#btn_check_code', function (e) {
		e.preventDefault();
		var activationCodeIsCorrect = false,
		    code = $('#register_activation_code').val();
		$.ajax({
			type: "POST",
			url: "assets/ajax/registrationValidation.php",
			data: "activation_code=" + code,
			success: function (result) {
				if (result !== 'true') {
					$('#register_error').show();
					$('#register_error').html(result);
					activationCodeIsCorrect = false;
				} else {
					$('#register_submit').show();
					$('#btn_check_code').hide();
					$('#register_error').hide();
					activationCodeIsCorrect = true;
				}
			}
		});
	});
	
	//ON SUBMIT: Check if all fields are correct
	$(document).on('click', '#register_submit', function (e) {
        emailIsUnique($('#register_email').val());
		if (!isValidEmailAddress($('#register_email').val()) || $('#register_password').isEmpty() || $('#register_confirm_password').isEmpty() || $('#register_password').val() !== $('#register_confirm_password').val() || !activationCodeIsCorrect) {
			e.preventDefault();
			$('#register_error').show();
			$('#register_error').html('Vul email en wachtwoord correct in.');
		}
	});
});