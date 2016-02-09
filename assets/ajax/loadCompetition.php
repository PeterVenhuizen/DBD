<?php                    
    require_once('../config.php');
    
    if (isset($_POST['teamId'])) {
    
        // Gather the team information based on 
        // the clicked team button    
        $teamId = mysql_real_escape_string($_POST['teamId']);
        $teamName = $mysqli->query("SELECT team_name FROM teams WHERE team_id = '$teamId' LIMIT 1")->fetch_object()->team_name;
        //$competitionId = $mysqli->query("SELECT competition_id FROM competition WHERE team_id = '$teamId' AND current = 1")->fetch_object()->competition_id; 
        $competitionId = $mysqli->query("SELECT competition_id FROM competition WHERE team_id = '$teamId' AND current = 1");
        if ($competitionId->num_rows > 0) {
            $competitionId = $competitionId->fetch_object()->competition_id;   
        } else {
            $competitionId = 0;   
        }
  
        // Get the matches of this team
        $query = "SELECT * FROM matches WHERE team_id = :teamID AND competition_id = :competitionID ORDER BY match_date";
        $query_params = array('teamID' => mysql_real_escape_string($teamId), 'competitionID' => $competitionId);

        try {
            $stmt = $db->prepare($query);
            $stmt->execute($query_params);
        } catch (PDOException $ex) { die("Failed to run query: " . $ex->getMessage()); }  
  
        $competitionEcho = '<table id="competition">
            <tr>
                <th class="match_date">Datum</th>
                <th class="match_time">Tijd</th>
                <th class="match_teams">Teams</th>
                <th class="match_location">Locatie</th>
                <th class="match_result">Uitslag</th>
            </tr>';

        if ($stmt->rowCount() > 0) {
            foreach ($stmt as $match) {

                // Format the date and time of the match
                $matchDate = strtotime($match['match_date']);
                $matchTime = strtotime($match['match_time']);

                // Make the teams string
                if ($match['home']) { $game = $teamName . ' - ' . $match['opponent']; }
                else { $game = $match['opponent'] . ' - ' . $teamName; }

                // Get the score, if the match is played
                if ($match['played']) { $matchScore = $match['home_goals'] . ' - ' . $match['away_goals']; }
                else { $matchScore = ''; }

                // Determine if a match was a win, loss or draw
                if ($match['home'] && $match['played'] && $match['home_goals'] > $match['away_goals'] || !$match['home'] && $match['away_goals'] > $match['home_goals']) { 
                    $matchResult = 'win'; // Match was won
                } else if ($match['home'] && $match['played'] && $match['home_goals'] < $match['away_goals'] || !$match['home'] && $match['away_goals'] < $match['home_goals']) {
                    $matchResult = 'loss'; // Match was lost
                } else if ($match['played'] && $match['home_goals'] == $match['away_goals']) {
                    $matchResult = 'draw'; // Match was a draw
                } else { $matchResult = ''; }                    

                // Generate the match rows
                $competitionEcho .= '<tr>
                                    <td class="match_date">' . date('j M', $matchDate) . '</td>
                                    <td class="match_time">' . date('G:i', $matchTime) . '</td>
                                    <td class="match_teams">' . $game . '</td>
                                    <td class="match_location">' . $match['location'] . '</td>
                                    <td class="match_result ' . $matchResult . '">' . $matchScore . '</td>
                                </tr>';
            }
        } else {
            $competitionEcho .= '<tr><td colspan="4">Het competitie programma voor dit team is helaas nog niet bekend!</td></tr>';
        }
        
        // Close the table
        $competitionEcho .= '</table>';


        /* Calculate the competition stats for this team */
        $nMatches = mysql_num_rows(mysql_query("SELECT * FROM matches WHERE team_id = '$teamId' AND competition_id = '$competitionId' AND played"));
        if ($nMatches > 0) {
            $nWon = mysql_num_rows(mysql_query("	SELECT * FROM matches WHERE team_id = '$teamId' AND home = 1 AND home_goals > away_goals AND played AND competition_id = '$competitionId'
                                                    UNION
                                                    SELECT * FROM matches WHERE team_id = '$teamId' AND home = 0 AND home_goals < away_goals AND played AND competition_id = '$competitionId'"));
            $nLost = mysql_num_rows(mysql_query("	SELECT * FROM matches WHERE team_id = '$teamId' AND home = 1 AND home_goals < away_goals AND played AND competition_id = '$competitionId'
                                                    UNION
                                                    SELECT * FROM matches WHERE team_id = '$teamId' AND home = 0 AND home_goals > away_goals AND played AND competition_id = '$competitionId'"));
            $nDraw = mysql_num_rows(mysql_query("	SELECT * FROM matches WHERE team_id = '$teamId' AND home = 1 AND home_goals = away_goals AND played AND competition_id = '$competitionId'
                                                    UNION
                                                    SELECT * FROM matches WHERE team_id = '$teamId' AND home = 0 AND home_goals = away_goals AND played AND competition_id = '$competitionId'"));

            $widthWon = 60 * ($nWon/$nMatches);
            $widthDraw = 60 * ($nDraw/$nMatches);
            $widthLoss = 60 * ($nLost/$nMatches);

            $competitionEcho .= ' <fieldset id="win_loss_ratio">
                        <legend>Gespeelde wedstrijden</legend>
                        <label>Gewonnen: </label><span id="won_match" class="win" style="width: ' . $widthWon . '%">' . $nWon . '</span><br>
                        <label>Gelijk: </label><span id="draw_match" class="draw" style="width: ' . $widthDraw . '%">' . $nDraw . '</span><br>
                        <label>Verloren: </label><span id="lost_match" class="loss" style="width: ' . $widthLoss . '%">' . $nLost . '</span>
                    </fieldset>';                 


            $goals_for = $mysqli->query("	SELECT SUM(goals) AS goals FROM (
                                            SELECT home_goals AS goals FROM matches WHERE team_id = '$teamId' AND home = 1 AND played AND competition_id = '$competitionId'
                                            UNION ALL
                                            SELECT away_goals AS goals FROM matches WHERE team_id = '$teamId' AND home = 0 AND played AND competition_id = '$competitionId'
                                            ) AS goals")->fetch_object()->goals;

            $goals_against = $mysqli->query("	SELECT SUM(goals) AS goals FROM (
                                                SELECT away_goals AS goals FROM matches WHERE team_id = '$teamId' AND home = 1 AND played AND competition_id = '$competitionId'
                                                UNION ALL
                                                SELECT home_goals AS goals FROM matches WHERE team_id = '$teamId' AND home = 0 AND played AND competition_id = '$competitionId'
                                                ) AS goals")->fetch_object()->goals;
            $totalGoals = $goals_for + $goals_against;   

            $widthFor = 365 * ($goals_for/$totalGoals) + 5;
            $widthAgainst = 365 * ($goals_against/$totalGoals) + 5;

            $competitionEcho .= '	<fieldset id="goals">
                        <legend>Doelpunten </legend>
                        <label>Voor: </label><span class="win" style="width: ' . $widthFor . 'px;">' . $goals_for . '</span>
                        <br>
                        <label>Tegen: </label><span class="loss" style="width: ' . $widthAgainst . 'px;">' . $goals_against . '</span>
                    </fieldset>';
        }
        
        echo $competitionEcho;
    }
?>