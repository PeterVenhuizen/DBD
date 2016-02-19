$(document).ready(function() {
	
	$(document).on('click', '.btn_user_rights', function() {
		var id = $(this).attr('value');
		var rights = $(this).parent().prev().find('option.user_rights:selected').val();
		$.post('actions/ajax.updateUsers.php', { id: id, rights: rights, action: 'edit' }).done(function(data) {
			$('#users').html(data);
		});
	});
	
	$(document).on('click', '.btn_user_delete', function() {
		var id = $(this).attr('value');
		if (confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')) {
			$.post('actions/ajax.updateUsers.php', { id: id, action: 'delete' }).done(function(data) {
				$('#users').html(data);
			});
		}
	});
	
	$(document).on('click', '#btn_download', function() {
		window.location.href = 'actions/downloadUsers.php';
	});
	
});
