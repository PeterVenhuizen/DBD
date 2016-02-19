<?php
	require_once('../../assets/config.php');
	require 'functions.php';
	
	// Update user rights
	if (isset($_POST['id']) && $_POST['action'] == 'edit') {
		
		try {
			$stmt = $db->prepare('UPDATE users SET rights = :rights WHERE id = :id');
			$stmt->execute(array('rights' => $_POST['rights'], 'id' => $_POST['id']));
		} catch (PDOException $ex) { }
	
		// Save to log
		add_to_log($db, array('user' => $_SESSION['user']['email'], 'action' => 'EDIT', 'page' => 'cms.users.php', 'desc' => $_POST['id']));

	// Delete user
	} else if (isset($_POST['id']) && $_POST['action'] == 'delete') {
		
		try {
			$stmt = $db->prepare("DELETE FROM users WHERE id = :id");
			$stmt->execute(array('id' => $_POST['id']));
		} catch (PDOException $ex) { }
		
		// Save to log
		add_to_log($db, array('user' => $_SESSION['user']['email'], 'action' => 'DELETE', 'page' => 'cms.users.php', 'desc' => $_POST['id']));
		
	}

	// Load updated table
	echo '	<tr>
				<th class="user_name">Naam</th>
				<th class="user_email">Email</th>
				<th class="user_rights">Rechten</th>
				<th></th>
				<th></th>
			</tr>';
	try {
		$stmt = $db->prepare('SELECT id, first_name, prefix, last_name, email, rights FROM users ORDER BY first_name');
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			foreach ($stmt as $user) {
				echo '	<tr>
							<td class="user_name">' . $user['first_name'] . ' ' . $user['prefix'] . ' ' . $user['last_name'] . '</td>
							<td class="user_email">' . $user['email'] . '</td>
							<td class="user_rights">
								<select name="user_rights">
									<option class="user_rights" ' . ($user['rights'] == 0 ? "SELECTED" : "") . ' value="0">0</option>
									<option class="user_rights" ' . ($user['rights'] == 1 ? "SELECTED" : "") . ' value="1">1</option>
									<option class="user_rights" ' . ($user['rights'] == 2 ? "SELECTED" : "") . ' value="2">2</option>
									<option class="user_rights" ' . ($user['rights'] == 3 ? "SELECTED" : "") . ' value="3">3</option>
								</select>
							</td>
							<td>
								<button name="btn_user_rights" class="btn_edit btn_user_rights" value="' . $user['id'] . '"></button>
							</td>
							<td>
								<button name="btn_user_delete" class="btn_delete btn_user_delete" value="' . $user['id'] . '"></button>
							</td>
						</tr>';
			}
		}
	} catch (PDOException $ex) { }
?>
