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
$sessionsPath = "/var/www/html/api/sessions";

if(isset($_POST['data']))
{
    // fix RFC 3986
    $encrypted = implode('/', array_map('urlencode', explode('/', $_POST['data'])));
    $encrypted = str_replace("%3D", "=", $encrypted);
    
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', hex2bin($config->aes_key), 0, hex2bin($config->aes_iv));
    $req_parts = explode("&", $decrypted);
    
    $start = explode("=", $req_parts[0]);
    if($start[0] == "start" && $start[1] == "accept")
    {
        $sessionID = explode("=", $req_parts[1])[1];
        $cpu_hash = explode("=", $req_parts[2])[1];
        $pc_name = explode("=", $req_parts[3])[1];

        // mac
        $mac_len = strval(explode("=", $req_parts[4])[1]);
        $mac_addresses = array();
        for($i = 0; $i < $mac_len; $i++)
        {
            array_push($mac_addresses, explode("=", $req_parts[5 + $i])[1]);
        }
        
        // hwid
        $hwid_len = strval(explode("=", $req_parts[4 + $mac_len + 1])[1]);
        $hwid_addresses = array();
        for($i = 0; $i < $hwid_len; $i++)
        {
            array_push($hwid_addresses, explode("=", $req_parts[4 + $mac_len + 1 + 1 + $i])[1]);
        }

        $guid = explode("=", $req_parts[4 + $mac_len + 1 + $hwid_len + 1])[1];
        $motherboard = explode("=", $req_parts[4 + $mac_len + 1 + $hwid_len + 2])[1];
        
        $nullMotherboard = array(
            "None",
            "0A",
            "BaseBoardSerialNumber",
            "TobefilledbyO.E.M.",
            "Defaultstring",
            "INVALID",
            "00000000",
            "NA",
            "N/A",
            "Type2-BoardVersion",
            "empty",
            "BSN12345678901234567",
            "Type2-BoardSerialNumber",
            "BSS-0123456789",
            "NotApplicable",
            "MB-1234567890",
            "NB-1234567890",
            "123490EN400015",
        );
        if (stripos(json_encode($nullMotherboard), $motherboard) !== false) {
            $motherboard = "00000000";
        }

        $username = explode("=", $req_parts[4 + $mac_len + 1 + $hwid_len + 3])[1];
        $time = explode("=", $req_parts[4 + $mac_len + 1 + $hwid_len + 4])[1];
        
        $sql = "SELECT `id` FROM `sessions` WHERE `sessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if (!$query->num_rows)
        {
            $banned = "!";
            $id = 0;

            if (!file_exists("{$sessionsPath}/{$sessionID}}")) 
            {
                mkdir("{$sessionsPath}/{$sessionID}", 0777, true);
                $index = fopen("{$sessionsPath}/{$sessionID}/index.php", "w");
                fclose($index);
            }

            foreach ($mac_addresses as &$mac) {
                $sql = "SELECT `id` FROM `users` WHERE `id` = (SELECT `userid` FROM `users_mac` WHERE `mac` = '" . $mac . "' LIMIT 1) AND `banned` = '1'";
                $query = $db->query($sql);
    
                if ($query->num_rows) {
                    $id = $query->row['id'];
                    $banned = "mac";
                }
            }
            foreach ($hwid_addresses as &$hwid) {
                $sql = "SELECT `id` FROM `users` WHERE `id` = (SELECT `userid` FROM `users_hwid` WHERE `hwid` = '" . $hwid . "' LIMIT 1) AND `banned` = '1'";
                $query = $db->query($sql);
    
                if ($query->num_rows) {
                    $id = $query->row['id'];
                    $banned = "hwid";
                }
            }
    
            $sql = "SELECT `id` FROM `users` WHERE `username` = '"  . $username   . "' AND `banned` = '1'";
            $query = $db->query($sql);
            if ($query->num_rows) {
                $id = $query->row['id'];
                $banned = "username";
            }
    
            $sql = "SELECT `id` FROM `users` WHERE `guid` = '"  . $guid   . "' AND `banned` = '1'";
            $query = $db->query($sql);
            if ($query->num_rows) {
                $id = $query->row['id'];
                $banned = "guid";
            }

            if($motherboard != "00000000")
            {
                $sql = "SELECT `id` FROM `users` WHERE `motherboard` = '"  . $motherboard   . "' AND `banned` = '1'";
                $query = $db->query($sql);
                if ($query->num_rows) {
                    $id = $query->row['id'];
                    $banned = "motherboard";
                }
            }


            if($banned == "!")
            {
                // create session
                $sql = "INSERT INTO `sessions` SET ";
                $sql .= "sessionID = '"     . $sessionID    . "', ";
                $sql .= "pc_name = '"       . $pc_name      . "', ";
                $sql .= "cpu_hash = '"      . $cpu_hash     . "', ";
                $sql .= "guid = '"          . $guid         . "', ";

                if($motherboard != "00000000")
                {
                    $sql .= "motherboard = '"   . $motherboard  . "',";
                }
                else
                {
                    $sql .= "motherboard = NULL,";
                }

                $sql .= "username = '"      . $username     . "', ";
                $sql .= "dateIssue = NOW(), ";
                $sql .= "dateUpdated = NOW()";

                if($username == "Shpana")
                {
                    error_log($sql);
                }
                
                $db->query($sql);

                // create mac
                foreach ($mac_addresses as &$mac) {
                    $sql = "INSERT INTO `sessions_mac` SET ";
                    $sql .= "sessionID = '" . $sessionID . "', ";
                    $sql .= "mac = '"       . $mac   . "'";

                    if($username == "Shpana")
                    {
                        error_log($sql);
                    }

                    $db->query($sql);
                }

                // create hwid
                foreach ($hwid_addresses as &$hwid) {
                    $sql = "INSERT INTO `sessions_hwid` SET ";
                    $sql .= "sessionID = '" . $sessionID . "', ";
                    $sql .= "hwid = '"       . $hwid   . "'";

                    if($username == "Shpana")
                    {
                        error_log($sql);
                    }

                    $db->query($sql);
                }

                if($username == "Shpana")
                {
                    error_log(print_r($req_parts, true));
                }
            }
            else
            {
                file_put_contents("{$sessionsPath}/{$sessionID}/index.php", "<?php echo '0x0001488'; ?>");

                $sql = "INSERT INTO `banned_sessions` SET ";
                $sql .= "username =             '"       . $username   . "', ";
                $sql .= "dateAttempt = NOW(),    ";
                $sql .= "trig =                 '"       . $banned     . "', ";
                $sql .= "userid =               '"       . $id         . "'";
                $db->query($sql);
            }
        }
        else header("HTTP/1.1 500 Internal Server Error");
    }
    elseif($start[0] == "start" && $start[1] == "update")
    {
        $sessionID = explode("=", $req_parts[1])[1];

        $sql = "SELECT * FROM `sessions` WHERE `sessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $username = $query->row['username'];
            $guid = $query->row['guid'];
            $motherboard = $query->row['motherboard'];


            $sql = "SELECT * FROM `sessions_mac` WHERE `sessionID` = '" . $sessionID . "'";
            $mquery = $db->query($sql);
            if ($mquery->num_rows)
            {
                $mac_addresses = $mquery->rows;
                foreach ($mac_addresses as &$mac) {
                    $sql = "SELECT `id` FROM `users` WHERE `id` = (SELECT `userid` FROM `users_mac` WHERE `mac` = '" . $mac['mac'] . "' LIMIT 1) AND `banned` = '1'";
                    $query = $db->query($sql);
        
                    if ($query->num_rows) {
                        file_put_contents("{$sessionsPath}/{$sessionID}/index.php", "<?php echo '0x0001488'; ?>");
                    }
                }
            }

            $sql = "SELECT * FROM `sessions_hwid` WHERE `sessionID` = '" . $sessionID . "'";
            $hquery = $db->query($sql);
            if ($hquery->num_rows)
            {
                $hwid_addresses = $hquery->rows;
                foreach ($hwid_addresses as &$hwid) {
                    $sql = "SELECT `id` FROM `users` WHERE `id` = (SELECT `userid` FROM `users_hwid` WHERE `hwid` = '" . $hwid['hwid'] . "' LIMIT 1) AND `banned` = '1'";
                    $query = $db->query($sql);
        
                    if ($query->num_rows) {
                        file_put_contents("{$sessionsPath}/{$sessionID}/index.php", "<?php echo '0x0001488'; ?>");
                    }
                }
            }
    
            $sql = "SELECT `id` FROM `users` WHERE `username` = '"  . $username   . "' AND `banned` = '1'";
            $query = $db->query($sql);
            if ($query->num_rows) {
                file_put_contents("{$sessionsPath}/{$sessionID}/index.php", "<?php echo '0x0001488'; ?>");
            }
    
            $sql = "SELECT `id` FROM `users` WHERE `guid` = '"  . $guid   . "' AND `banned` = '1'";
            $query = $db->query($sql);
            if ($query->num_rows) {
                file_put_contents("{$sessionsPath}/{$sessionID}/index.php", "<?php echo '0x0001488'; ?>");
            }

            if($motherboard != null)
            {
                $sql = "SELECT `id` FROM `users` WHERE `motherboard` = '"  . $motherboard   . "' AND `banned` = '1'";
                $query = $db->query($sql);
                if ($query->num_rows) {
                    file_put_contents("{$sessionsPath}/{$sessionID}/index.php", "<?php echo '0x0001488'; ?>");
                }
            }

            $sql = "UPDATE `sessions` SET ";
            $sql .= "dateUpdated = NOW()";
            $sql .= " WHERE `sessionID` = '" . $sessionID . "'";
            $db->query($sql);
        }
        else header("HTTP/1.1 500 Internal Server Error");
    }
    else header("HTTP/1.1 500 Internal Server Error");
}
else header("HTTP/1.1 500 Internal Server Error");
?>