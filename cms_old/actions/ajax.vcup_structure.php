<?php 
	require_once('../../assets/config.php');
	
	if (isset($_POST['menuID'])) {
		$id = mysql_real_escape_string($_POST['menuID']);
		$query = mysql_query("SELECT * FROM vcup_menu WHERE id = '$id' LIMIT 1");
		
		while ($menu = mysql_fetch_array($query)) {
			echo '	<h2 class="h2header">Wijzig menu item</h2>
				
					<input type="hidden" name="menuID" value="' . $menu['id'] . '" />
					
					<label for="editMenuName" class="lbEditMenuName">Menu item naam: </label>
					<input type="text" name="editMenuName" id="editMenuName" maxlength="14" placeholder="E.g. Home" value="' . $menu['name'] . '" />

					<label for="itemOrder" class="lbSmallMenuItem">Volgorde: </label>
					<input type="text" name="itemOrder" id="itemOrder" size="2" maxlength="2" value="' . $menu['_order'] . '" />
					
					<label for="itemExpand" class="lbSmallMenuItem">Expand: </label>';
					
			if ($menu['expand']) {
				echo '	<input type="radio" name="itemExpand" id="itemExpand" value="1" CHECKED/>True
						<input type="radio" name="itemExpand" id="itemExpand" value="0"/>False';
			} else {
				echo '	<input type="radio" name="itemExpand" id="itemExpand" value="1" />True
						<input type="radio" name="itemExpand" id="itemExpand" value="0" CHECKED/>False
						
						<label for="menuURL" class="lbMenuURL">Menu link: </label>
						<input type="text" name="menuURL" id="menuURL" placeholder="E.g. contact.php" value="' . $menu['url'] . '" />';
			}
			
			echo '	<input type="submit" name="editMItem" id="editMItem" value="Wijzig" />
					<input type="submit" name="deleteMItem" id="deleteMItem" value="Verwijder" onclick="return confirm(\'Weet je zeker dat je dit wilt verwijderen?\')"/>';
		}
	
	} else if (isset($_POST['addID'])) {
		$id = mysql_real_escape_string($_POST['addID']);

		echo '	<h2 class="h2header">Nieuwe subpagina</h2>
				
				<input type="hidden" name="parentID" value="' . $id . '" />
				
				<label for="pageTitle" class="lbAddPage">Subpagina titel: </label>
				<input type="text" name="pageTitle" id="pageTitle" maxlength="18" placeholder="E.g. Bestuur" />
				
				<input type="submit" name="addPage" id="addPage" value="Maak" />';
	
	} else if (isset($_POST['newMenuPage'])) {
		echo '	<h2 class="h2header">Nieuw MENU ITEM</h2>
				
				<label for="editMenuName" class="lbEditMenuName">Menu item naam: </label>
				<input type="text" name="editMenuName" id="editMenuName" maxlength="14" placeholder="I.e. Home" value="" />

				<label for="itemOrder" class="lbSmallMenuItem">Volgorde: </label>
				<input type="text" name="itemOrder" id="itemOrder" size="2" maxlength="2" value="" />
				
				<label for="itemExpand" class="lbSmallMenuItem">Expand: </label>

				<input type="radio" name="itemExpand" id="itemExpand" value="1" />True
				<input type="radio" name="itemExpand" id="itemExpand" value="0" CHECKED/>False
					
				<label for="menuURL" class="lbMenuURL">Menu link: </label>
				<input type="text" name="menuURL" id="menuURL" placeholder="I.e. contact.php" value="" />

				<input type="submit" name="createMenuItem" id="createMenuItem" value="Maak aan" />';		
	}
	
?>