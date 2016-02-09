        <header id="page_header">
            <div id="header_wrapper">
                <!--<a href="http://www.debalderin.wur.nl/"><img src="assets/img/dbd_logo_100.png" id="img_logo" /></a>-->
                <a href="http://localhost/DBD/"><img src="assets/img/dbd_logo_100.png" id="img_logo"></a>
                <h1>W.S.K.V. Débaldérin</h1>
                <img src="assets/img/menu_expand_dense.png" id="img_menu" />
            </div>
		</header>
			
		<nav id="menu">
            <?php
                // Get the menu from database
                $menuEcho = '<ul>';
        
                // Retrieve the major menu items
                try {
                    $stmt = $db->prepare("SELECT * FROM menu ORDER BY _order");
                    $stmt->execute();
                } catch (PDOException $ex) { die(); }  

                // Iterate over menu items
                if ($stmt->rowCount() > 0) {
                    foreach($stmt as $row) {
                        // Check if menu contains a submenu
                        if ($row['expand']) { // There is a submenu
                            // Add the top level menu item
                            try {
                                $sub_stmt = $db->prepare("SELECT * FROM subpages WHERE parent_id = :parentId AND published ORDER BY _order");
                                $sub_stmt->bindValue('parentId', $row['id']);
                                $sub_stmt->execute();
                                if ($sub_stmt->rowCount() > 0) {
                                    if ($sub_stmt->rowCount() == 1) {
                                        $sub = $sub_stmt->fetch();
                                        $menuEcho .= '<li><a href="' . str_replace(' ', '_', $row['name']) . '/' . str_replace(' ', '_', $sub['name']) . '/">' . $row['name'] . '</a>';   
                                    } else {
                                        $do_once = true;
                                        foreach($sub_stmt as $sub) {
                                            if ($do_once) {
                                                $menuEcho .= '<li><a href="' . str_replace(' ', '_', $row['name']) . '/' . str_replace(' ', '_', $sub['name']) . '/" class="ul_expand">' . $row['name'] . '</a><span class="expand">+</span><ul>';   
                                            }
                                            $menuEcho .= '<li><a href="' . str_replace(' ', '_', $row['name']) . '/' . str_replace(' ', '_', $sub['name']) . '/">' . $sub['name'] . '</a></li>';
                                            $do_once = false;
                                        }
                                        $menuEcho .= '</ul>';
                                    }
                                }  
                            } catch (PDOException $ex) { die(); }

                            $menuEcho .= '</li>';
                        } else { // No submenu
                            // Check if there is a special URL
                            if (empty($row['url'])){ $menuEcho .= '<li><a href="' . str_replace(' ', '_', $row['name']) . '">' . $row['name'] . '</a></li>'; }
                            else { $menuEcho .= '<li><a href="' . $row['url'] . '">' . $row['name'] . '</a></li>'; }
                        }
                    }
                }

                $menuEcho .= '      <li id="hangout">
                                        <a href="Leden/"><img src="assets/img/debbies_hangout_small.png" id="debbies_hangout" /></a>
                                    </li>';

                echo $menuEcho . '</ul>';
            ?>
		</nav>
