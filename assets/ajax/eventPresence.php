<?php 
	require_once('../config.php');
	
	if (isset($_POST['sub_id']) and isset($_POST['activity_id'])) {
		$sub_id = mysql_real_escape_string($_POST['sub_id']);
		$act_id = mysql_real_escape_string($_POST['activity_id']);
		
		$current_subs = $mysqli->query("SELECT subscribers FROM events WHERE id = '$act_id'")->fetch_object()->subscribers;
		$new_subs = $current_subs . $sub_id . ';';
		$explode_subs = explode(';', $new_subs);
		$uniq_subs = array_unique($explode_subs);
		$new_subs = implode(';', $uniq_subs);

		$query = "UPDATE events SET subscribers = :new_subs WHERE id = :act_id";
		$query_params = array(':new_subs' => $new_subs, ':act_id' => $act_id);
		try {
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);	
		} catch(PDOException $ex)  {
			#Do nothing
			die("Failed to run query: " . $ex->getMessage()); 
		}				
		
	} else if (isset($_POST['unsub_id']) and isset($_POST['activity_id'])) {
		$sub_id = mysql_real_escape_string($_POST['unsub_id']);
		$act_id = mysql_real_escape_string($_POST['activity_id']);
		
		$current_subs = $mysqli->query("SELECT subscribers FROM events WHERE id = '$act_id'")->fetch_object()->subscribers;
		$subs_array = explode(';', $current_subs);
		$new_subs = '';
		foreach($subs_array as &$sub) {
			if ($sub_id != $sub and $sub != '') { $new_subs .= $sub . ';'; }
		}
		
		$query = "UPDATE events SET subscribers = :new_subs WHERE id = :act_id";
		$query_params = array(':new_subs' => $new_subs, ':act_id' => $act_id);
		try {
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);	
		} catch(PDOException $ex)  {
			#Do nothing
			die("Failed to run query: " . $ex->getMessage()); 
		}			
		
	}
?>
