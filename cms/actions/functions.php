<?php
	function add_to_log($db, $v) {
		
		$ip_addr = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'] );
		try {
			$stmt = $db->prepare("INSERT INTO logs (_user, _action, page, _desc, ip_addr) VALUES (:user, :action, :page, :desc, :ip_addr)");
			$stmt->execute(array(':user' => $v['user'], ':action' => $v['action'], ':page' => $v['page'], ':desc' => $v['desc'], 'ip_addr' => $ip_addr));
		} catch (PDOException $ex) { echo $ex->getMessage(); }
		
	}
?>
