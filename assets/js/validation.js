$(document).ready(function () {
	
	// Check if date is valid
	jQuery.fn.extend({
		is_valid_date: function() {
			var isValid = false;
			
			var date = $(this).val();
			var splitDate = date.split('-')
			
			var day = parseInt(splitDate[0]);
			var month = parseInt(splitDate[1]);
			var year = parseInt(splitDate[2]);
			
			// Check if it is a leap year
			var isLeap = new Date(year, 1, 29).getMonth() == 1
			
			//Check date
			var pattern = new RegExp('[1|2][0-9]{3}');
			if (year.toString().length == 4 && pattern.test(year)) {						
				if (day <= 31 && $.inArray(month, [1, 3, 5, 7, 8, 10, 12]) != -1) {
					isValid = true;
				} else if (day <= 30 && $.inArray(month, [4, 6, 9, 11]) != -1) {
					isValid = true;
				} else if (day == 29 && month == 2 && isLeap) {
					isValid = true;
				} else if (day <= 28 && month == 2) {
					isValid = true;	
				} 
			}

			// Add field formatting
			if (isValid) {
				$(this).removeClass('error');
				$(this).addClass('correct');
			} else {
				$(this).removeClass('correct');
				$(this).addClass('error');						
			}

			return isValid
		}
	})
    
	// Check if zip code is valid
	jQuery.fn.extend({
		is_valid_zip_code: function() {
			var zip_code = $(this).val();
			var pattern = new RegExp(/[0-9]{4}[a-zA-Z]{2}/i);
			isValid = pattern.test(zip_code);
		
			// Add field formatting
			if (isValid) {
				$(this).removeClass('error');
				$(this).addClass('correct');
			} else {
				$(this).removeClass('correct');
				$(this).addClass('error');						
			}
			
			return isValid;				
		}
	})    
    
    // Check if there is field input
    jQuery.fn.extend({
		isEmpty: function () {
			var value = $.trim($(this).val());
			if (value) {
				$(this).switchClass('error', 'correct');
				return false;
			} else {
				$(this).switchClass('correct', 'error');
				return true;
			}
		}
	});
	
    // Check if the field input is 
    // a valid email
	jQuery.fn.extend({
		isValidEmail: function () {
			var email = $.trim($(this).val()), pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
			if (pattern.test(email)) {
				$(this).switchClass('error', 'correct');
				return true;
			} else {
				$(this).switchClass('correct', 'error');
				return false;
			}
		}
	});
    
    
    // Validate the contact form fields
    // on focusout and submit 
    // Validate if fields are not empty
    $(document).on('focusout', '#contact_name, #contact_subject, #contact_message', function () {
        $(this).isEmpty();
    });
    
    // Validate email validity
    $(document).on('focusout', '#contact_email', function () {
        $(this).isValidEmail();
    });
    
    // Check all fields before submit
    $(document).on('click', '#contact_submit', function (e) {
        if ($('#contact_name').isEmpty() || !$('#contact_email').isValidEmail() || $('#contact_subject').isEmpty() || $('#contact_message').isEmpty()) {
            e.preventDefault();
        } else {
            //alert('Bedankt voor je email!');
        }
    });
});