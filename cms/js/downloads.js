$(document).ready(function() {
	$.post('actions/ajax.loadDownloads.php', function(result) {
		$('#list_downloads').html(result);
	});
	
	// Update downloads if upload is finished
	$('#nr_uploads').bind("DOMSubtreeModified", function() {
		setTimeout(function() {
			$.post('actions/ajax.loadDownloads.php', function(result) {
				$('#list_downloads').html(result);
			});	
		}, 100);			
	});
	
	// Delete file and refresh
	$(document).on('click', '.delete_download', function() {
		var filename = $(this).attr('id');
		if (confirm('Weet je zeker dat je "'+filename+'" wilt verwijderen?')) {
			$.ajax({
				type: 'POST',
				url: 'actions/ajax.loadDownloads.php',
				data: 'delete_file='+filename,
				success: function(result) {
					$('#list_downloads').html(result);
				}
			});
		}					
	});	
});
