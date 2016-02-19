<?php 
	require_once('../../assets/config.php');

	# Update user rights
	if (isset($_POST['user_id']) AND isset($_POST['user_rights'])) {
		$user_id = mysql_real_escape_string($_POST['user_id']);
		$user_rights = mysql_real_escape_string($_POST['user_rights']);
		
		mysql_query("UPDATE users SET rights = '$user_rights' WHERE id = '$user_id'") or die(mysql_error());
	}
	
	#Delete user
	if (isset($_POST['delete_id'])) {
		$id = mysql_real_escape_string($_POST['delete_id']);
		$query = "DELETE FROM users WHERE id = :id";
		$query_params = array(':id' => $id);
		
		try {
			$stmt = $db->prepare($query);
			$stmt->execute($query_params);	
		} catch(PDOException $ex)  {
			#Do nothing
		}		
	}
	
	
	echo '
	<tr>
		<th>Naam</th>
		<th>Email</th>
		<th>Rechten</th>
		<th></th>
		<th></th>
	</tr>';
		$users = mysql_query("SELECT * FROM users ORDER BY first_name");
		while ($user = mysql_fetch_array($users)) {
			echo '	<tr>
						<td class="userName">' . $user['first_name'] . ' ' . $user['prefix'] . ' ' . $user['last_name'] . '</td>
						<td class="userEmail">' . $user['email'] . ' </td>
						<td class="userRights">
							<select name="user_rights">';
	?>						
								<option class="user_rights <?php echo $user['id']; ?>" value="0" <?php if ($user['rights'] == 0) { echo "SELECTED"; } ?>>0</option>
								<option class="user_rights <?php echo $user['id']; ?>" value="1" <?php if ($user['rights'] == 1) { echo "SELECTED"; } ?>>1</option>
								<option class="user_rights <?php echo $user['id']; ?>" value="2" <?php if ($user['rights'] == 2) { echo "SELECTED"; } ?>>2</option>
								<option class="user_rights <?php echo $user['id']; ?>" value="3" <?php if ($user['rights'] == 3) { echo "SELECTED"; } ?>>3</option>																																				
	<?php												
			echo '			</select>
							<!--<input type="text" name="user_rights" class="user_rights ' . $user['id'] . '" value="' . $user['rights'] . '" /></td>-->
						<td class="userEdit">
							<button type="submit" name="editUserRights" class="btn_edit" value="' . $user['id'] .'"></button>
						</td>
						<td class="userDelete">
							<button name="btn_delete_user" class="btn_remove" value="' . $user['id'] . '"></button>
						</td>
					</tr>';
		}

?>