<?php
/*
RockAnticheat - multicomplex SA:MP anti-cheat system
with innovative technologies and infallible verdicts.

Developed by savvin & 0Z0SK0

(c) 2023
*/

require_once('../engine/main/config.php');
require_once('../engine/main/db.php');

$config = new Config();
$db = new DB($config->db_type, $config->db_hostname, $config->db_username, $config->db_password, $config->db_database);

$response = array();

$apiKey = $_SERVER['HTTP_API_KEY'];
if($apiKey == $config->api_key)
{
    if(isset($_GET['state']))
    {
        if($_GET['state'] == "connected")
        {
            $sql = "SELECT * FROM `sessions` WHERE `username` = '" . $_GET['name'] . "'";
            $query = $db->query($sql);

            if ($query->num_rows)
            {
                $session_id = $query->row['sessionID'];

                $sql = "SELECT * FROM `users` WHERE `username` = '" . $_GET['name'] . "' OR `guid` = '" . $query->row['guid'] . "'";
                $uquery = $db->query($sql);

                if (!$uquery->num_rows)
                {
                    $sql = "INSERT INTO `users` SET ";
                    $sql .= "username = '"          . $query->row['username']    . "', ";
                    $sql .= "lastSessionID = '"     . $session_id                . "', ";
                    $sql .= "pc_name = '"           . $query->row['pc_name']     . "', ";
                    $sql .= "cpu_hash = '"          . $query->row['cpu_hash']    . "', ";
                    $sql .= "motherboard =      '"  . $query->row['motherboard'] . "', ";
                    $sql .= "guid = '"              . $query->row['guid']        . "', ";
                    $sql .= "dateCreated = NOW()";
                    $db->query($sql);
                }
                else
                {
                    if($uquery->row['banned'] != '1')
                    {
                        $sql = "UPDATE `users` SET ";
                        $sql .= "lastSessionID =    '"  . $session_id                . "', ";
                        $sql .= "pc_name =          '"  . $query->row['pc_name']     . "', ";
                        $sql .= "cpu_hash =         '"  . $query->row['cpu_hash']    . "', ";
                        $sql .= "motherboard =      '"  . $query->row['motherboard'] . "', ";
                        $sql .= "guid =             '"  . $query->row['guid']        . "' ";
                        $sql .= "WHERE `username` = '"  . $query->row['username']    . "'";
                        $db->query($sql);
                    }
                    else
                    {
                        /*
                        $response = array(
                            'status' => false,
                            'error'  => 'Session [nick: ' . $_GET['name'] .  '] banned'
                        );
                        */                    

                        // user banned
                        $response = array(
                            'status' => false,
                            'error'  => 'Session [nick: ' . $_GET['name'] .  '] not found'
                        );
                        die($response);

                    }
                }
                
                $sql = "SELECT * FROM `users` WHERE `username` = '" . $_GET['name'] . "'";
                $uquery = $db->query($sql);
                $user_id = $uquery->row['id'];

                $sql = "SELECT * FROM `sessions_mac` WHERE `sessionID` = '" . $session_id . "'";
                $query = $db->query($sql);
                $rows = $query->rows;

                foreach($rows as $row)
                {
                    $sql = "SELECT * FROM `users_mac` WHERE `mac` = '" . $row['mac'] . "' LIMIT 1";
                    $query = $db->query($sql);

                    if (!$query->num_rows)
                    {
                        $sql = "INSERT INTO `users_mac` SET ";
                        $sql .= "userid = '"  . $user_id  . "', ";
                        $sql .= "mac = '"     . $row['mac'] . "'";
                        $db->query($sql);
                    }
                }

                $sql = "SELECT * FROM `sessions_hwid` WHERE `sessionID` = '" . $session_id . "'";
                $query = $db->query($sql);
                $rows = $query->rows;

                foreach($rows as $row)
                {
                    $sql = "SELECT * FROM `users_hwid` WHERE `hwid` = '" . $row['hwid'] . "' LIMIT 1";
                    $query = $db->query($sql);

                    if (!$query->num_rows)
                    {
                        $sql = "INSERT INTO `users_hwid` SET ";
                        $sql .= "userid = '"  . $user_id  . "', ";
                        $sql .= "hwid = '"    . $row['hwid'] . "'";
                        $db->query($sql);
                    }
                }

                $response = array(
                    'status' => true,
                    'success'  => 'Session #' . $session_id .  ' authorized as User #' . $user_id
                );
            }
            else
            {
                $response = array(
                    'status' => false,
                    'error'  => 'Session [nick: ' . $_GET['name'] .  '] not found'
                );
            }
        }
        elseif($_GET['state'] == "disconnected")
        {
            $sql = "SELECT `sessionID` FROM `sessions` WHERE `username` = '" . $_GET['name'] . "'";
            $query = $db->query($sql);

            if ($query->num_rows)
            {
                $sql = "DELETE FROM `sessions` WHERE `sessionID` = '" . $query->row['sessionID'] . "'";
                $db->query($sql);

                $sql = "DELETE FROM `sessions_mac` WHERE `sessionID` = '" . $query->row['sessionID'] . "'";
                $db->query($sql);

                $sql = "DELETE FROM `sessions_hwid` WHERE `sessionID` = '" . $query->row['sessionID'] . "'";
                $db->query($sql);

                $response = array(
                    'status' => true,
                    'success'  => 'Session #' . $query->row['sessionID'] .  ' disconnected'
                );
            }
            else
            {
                $response = array(
                    'status' => false,
                    'error'  => 'Session [nick: ' . $_GET['name'] .  '] not found'
                );
            }
        }
        elseif($_GET['state'] == "launch")
        {
            $sql = "SELECT COUNT(*) AS count FROM `sessions`";
            $query = $db->query($sql);
            $sessions_count = $query->row['count'];

            $sql = "DELETE FROM `sessions`";
            $db->query($sql);

            $sql = "DELETE FROM `sessions_mac`";
            $db->query($sql);

            $sql = "DELETE FROM `sessions_hwid`";
            $db->query($sql);

            $response = array(
                'status' => true,
                'success'  => 'All ' . $sessions_count . ' sessions successfully deleted'
            );
        }
        else
        {
            $response = array(
                'status' => false,
                'error'  => 'Wrong state'
            );
        }
    }
    else
    {
        $response = array(
            'status' => false,
            'error'  => 'Wrong Arguments'
        );
    }
}
else
{
    $response = array(
        'status' => false,
        'error'  => 'Wrong API key'
    );
}
die(json_encode($response));
?>