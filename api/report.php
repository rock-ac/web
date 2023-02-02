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

if(isset($_POST['data']))
{
    // fix RFC 3986
    $encrypted = implode('/', array_map('urlencode', explode('/', $_POST['data'])));
    $encrypted = str_replace("%3D", "=", $encrypted);
    
    $decrypted = openssl_decrypt($encrypted, 'aes-256-cbc', hex2bin($config->aes_key), 0, hex2bin($config->aes_iv));
    $req_parts = explode("&", $decrypted);

    $mode = explode("=", $req_parts[0]);

    // MEMORY
    if($mode[0] == "mode" && $mode[1] == "memory")
    {
        $base_address = explode("=",    $req_parts[1])[1];
        $region_size = explode("=",     $req_parts[2])[1];
        $characteristics = explode("=", $req_parts[3])[1];
        $name = explode("=",            $req_parts[4])[1];
        $hash = explode("=",            $req_parts[5])[1];
        $sessionID = explode("=",       $req_parts[6])[1];
        $username = explode("=",        $req_parts[7])[1];
        $time = explode("=",            $req_parts[8])[1];

        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_memory` WHERE `base_address` = '" . $base_address . "' AND `region_size` = '" . $region_size . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_memory` SET ";
                $sql .= "base_address = '"        . $base_address . "', ";
                $sql .= "region_size = '"         . $region_size   . "', ";
                $sql .= "characteristics = '"     . $characteristics  . "', ";
                $sql .= "name = '"                . $name      . "', ";
                $sql .= "hash = '"                . $hash  . "', ";
                $sql .= "sessionID = '"           . $sessionID  . "', ";
                $sql .= "userid = '"              . $userid  . "', ";
                $sql .= "username = '"            . $username . "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_memory` WHERE `base_address` = '" . $base_address . "' AND `region_size` = '" . $region_size . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_memory` SET ";
                $sql .= "base_address = '"        . $base_address . "', ";
                $sql .= "region_size = '"         . $region_size   . "', ";
                $sql .= "characteristics = '"     . $characteristics  . "', ";
                $sql .= "name = '"                . $name      . "', ";
                $sql .= "hash = '"                . $hash  . "', ";
                $sql .= "sessionID = '"           . $sessionID  . "', ";
                $sql .= "username = '"            . $username . "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
    }

    // MODULE
    elseif($mode[0] == "mode" && $mode[1] == "module")
    {
        $filename = explode("=",     $req_parts[1])[1];
        $path = explode("=",         $req_parts[2])[1];
        $hash = explode("=",         $req_parts[3])[1];
        $lastChange = explode("=",   $req_parts[4])[1];
        $permission = explode("=",   $req_parts[5])[1];
        $base_address = explode("=", $req_parts[6])[1];
        $publisher = explode("=",    $req_parts[7])[1];
        $size = explode("=",         $req_parts[8])[1];
        $sessionID = explode("=",    $req_parts[9])[1];
        $username = explode("=",     $req_parts[10])[1];
        $time = explode("=",         $req_parts[11])[1];

        // fix path
        $path = urlencode($path);
        $path = str_replace("%3A", ":", $path);
        $path = str_replace("%5C", "/", $path);
        $path = str_replace("%2F", "/", $path);

        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_module` WHERE `filename` = '" . $filename . "' AND `path` = '" . $path . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_module` SET ";
                $sql .= "filename = '"      . $filename .     "', ";
                $sql .= "path = '"          . $path .         "', ";
                $sql .= "hash = '"          . $hash .         "', ";
                $sql .= "lastChange = '"    . $lastChange .   "', ";
                $sql .= "size = '"          . $size .         "', ";
                $sql .= "permission = '"    . $permission .   "', ";
                $sql .= "base_address = '"  . $base_address . "', ";
                $sql .= "publisher = '"     . $publisher .    "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "userid = '"        . $userid .       "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_module` WHERE `filename` = '" . $filename . "' AND `path` = '" . $path . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_module` SET ";
                $sql .= "filename = '"      . $filename .     "', ";
                $sql .= "path = '"          . $path .         "', ";
                $sql .= "hash = '"          . $hash .         "', ";
                $sql .= "lastChange = '"    . $lastChange .   "', ";
                $sql .= "size = '"          . $size .         "', ";
                $sql .= "permission = '"    . $permission .   "', ";
                $sql .= "base_address = '"  . $base_address . "', ";
                $sql .= "publisher = '"     . $publisher .    "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }

        $sql = "SELECT `id` FROM `reported_sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
        $query = $db->query($sql);
        if (!$query->num_rows)
        {
            $sql = "SELECT * FROM `sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);

            // create report
            $sql = "INSERT INTO `reported_sessions` SET ";
            $sql .= "sessionID = '"    . $query->row['sessionID']     . "', ";
            $sql .= "guid = '"         . $query->row['guid']          . "', ";
            $sql .= "motherboard = '"  . $query->row['motherboard']   . "', ";
            $sql .= "username = '"     . $query->row['username']      . "', ";
            $sql .= "dateIssue = '"    . $query->row['dateIssue']     . "'";
            $db->query($sql);
        }
    }

    // CLEO
    elseif($mode[0] == "mode" && $mode[1] == "cleo") // CHANGE TO CLEO IN DLL !!!!!!!!!
    {
        $filename = explode("=",     $req_parts[1])[1];
        $path = explode("=",         $req_parts[2])[1];
        $hash = explode("=",         $req_parts[3])[1];
        $lastChange = explode("=",   $req_parts[4])[1];
        $size = explode("=",         $req_parts[5])[1];
        $permission = explode("=",   $req_parts[6])[1];
        $sessionID = explode("=",    $req_parts[7])[1];
        $username = explode("=",     $req_parts[8])[1];
        $time = explode("=",         $req_parts[9])[1];

        // fix path
        $path = urlencode($path);
        $path = str_replace("%3A", ":", $path);
        $path = str_replace("%5C", "/", $path);
        $path = str_replace("%2F", "/", $path);
        
        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_cleo` WHERE `filename` = '" . $filename . "' AND `path` = '" . $path . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_cleo` SET ";
                $sql .= "filename = '"      . $filename .     "', ";
                $sql .= "path = '"          . $path .         "', ";
                $sql .= "hash = '"          . $hash .         "', ";
                $sql .= "lastChange = '"    . $lastChange .   "', ";
                $sql .= "size = '"          . $size .         "', ";
                $sql .= "permission = '"    . $permission .   "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "userid = '"        . $userid .       "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_cleo` WHERE `filename` = '" . $filename . "' AND `path` = '" . $path . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_cleo` SET ";
                $sql .= "filename = '"      . $filename .     "', ";
                $sql .= "path = '"          . $path .         "', ";
                $sql .= "hash = '"          . $hash .         "', ";
                $sql .= "lastChange = '"    . $lastChange .   "', ";
                $sql .= "size = '"          . $size .         "', ";
                $sql .= "permission = '"    . $permission .   "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }

        $sql = "SELECT `id` FROM `reported_sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
        $query = $db->query($sql);
        if (!$query->num_rows)
        {
            $sql = "SELECT * FROM `sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);

            // create report
            $sql = "INSERT INTO `reported_sessions` SET ";
            $sql .= "sessionID = '"    . $query->row['sessionID']     . "', ";
            $sql .= "guid = '"         . $query->row['guid']          . "', ";
            $sql .= "motherboard = '"  . $query->row['motherboard']   . "', ";
            $sql .= "username = '"     . $query->row['username']      . "', ";
            $sql .= "dateIssue = '"    . $query->row['dateIssue']     . "'";
            $db->query($sql);
        }
    }

    // SAMPFUNCS
    elseif($mode[0] == "mode" && $mode[1] == "sampfuncs")
    {
        $filename = explode("=",     $req_parts[1])[1];
        $path = explode("=",         $req_parts[2])[1];
        $hash = explode("=",         $req_parts[3])[1];
        $lastChange = explode("=",   $req_parts[4])[1];
        $size = explode("=",         $req_parts[5])[1];
        $permission = explode("=",   $req_parts[6])[1];
        $sessionID = explode("=",    $req_parts[7])[1];
        $username = explode("=",     $req_parts[8])[1];
        $time = explode("=",         $req_parts[9])[1];

        // fix path
        $path = urlencode($path);
        $path = str_replace("%3A", ":", $path);
        $path = str_replace("%5C", "/", $path);
        $path = str_replace("%2F", "/", $path);

        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_sampfuncs` WHERE `filename` = '" . $filename . "' AND `path` = '" . $path . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_sampfuncs` SET ";
                $sql .= "filename = '"      . $filename .     "', ";
                $sql .= "path = '"          . $path .         "', ";
                $sql .= "hash = '"          . $hash .         "', ";
                $sql .= "lastChange = '"    . $lastChange .   "', ";
                $sql .= "size = '"          . $size .         "', ";
                $sql .= "permission = '"    . $permission .   "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "userid = '"        . $userid .       "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_sampfuncs` WHERE `filename` = '" . $filename . "' AND `path` = '" . $path . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_sampfuncs` SET ";
                $sql .= "filename = '"      . $filename .     "', ";
                $sql .= "path = '"          . $path .         "', ";
                $sql .= "hash = '"          . $hash .         "', ";
                $sql .= "lastChange = '"    . $lastChange .   "', ";
                $sql .= "size = '"          . $size .         "', ";
                $sql .= "permission = '"    . $permission .   "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }

        $sql = "SELECT `id` FROM `reported_sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
        $query = $db->query($sql);
        if (!$query->num_rows)
        {
            $sql = "SELECT * FROM `sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);

            // create report
            $sql = "INSERT INTO `reported_sessions` SET ";
            $sql .= "sessionID = '"    . $query->row['sessionID']     . "', ";
            $sql .= "guid = '"         . $query->row['guid']          . "', ";
            $sql .= "motherboard = '"  . $query->row['motherboard']   . "', ";
            $sql .= "username = '"     . $query->row['username']      . "', ";
            $sql .= "dateIssue = '"    . $query->row['dateIssue']     . "'";
            $db->query($sql);
        }
    }

    // VM
    elseif($mode[0] == "mode" && $mode[1] == "vm")
    {
        $name = explode("=",      $req_parts[1])[1];
        $sessionID = explode("=", $req_parts[2])[1];
        $username = explode("=",  $req_parts[3])[1];
        $time = explode("=",      $req_parts[4])[1];

        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_vm` WHERE `name` = '" . $name . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_vm` SET ";
                $sql .= "name = '"          . $name .         "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "userid = '"        . $userid .       "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_vm` WHERE `name` = '" . $name . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_vm` SET ";
                $sql .= "name = '"          . $name .         "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
    }

    // WINDOW
    elseif($mode[0] == "mode" && $mode[1] == "window")
    {
        $title = explode("=",       $req_parts[1])[1];
        $hwnd = explode("=",        $req_parts[2])[1];
        $visible = explode("=",     $req_parts[3])[1];
        $description = explode("=", $req_parts[4])[1];
        $path =     explode("=",    $req_parts[5])[1];
        $sessionID = explode("=",   $req_parts[6])[1];
        $username = explode("=",    $req_parts[7])[1];
        $time = explode("=",        $req_parts[8])[1];

        // fix path
        $path = urlencode($path);
        $path = str_replace("%3A", ":", $path);
        $path = str_replace("%5C", "/", $path);
        $path = str_replace("%2F", "/", $path);

        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_process` WHERE `title` = '" . $title . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_process` SET ";
                $sql .= "title = '"         . $title .        "', ";
                $sql .= "hwnd = '"          . $hwnd .         "', ";
                $sql .= "visible = '"       . $visible .      "', ";
                $sql .= "description = '"   . $description .  "', ";
                $sql .= "path = '"          . $path        .  "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "userid = '"        . $userid .       "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_process` WHERE `title` = '" . $title . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_process` SET ";
                $sql .= "title = '"         . $title .        "', ";
                $sql .= "hwnd = '"          . $hwnd .         "', ";
                $sql .= "visible = '"       . $visible .      "', ";
                $sql .= "description = '"   . $description .  "', ";
                $sql .= "path = '"          . $path        .  "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }

        $sql = "SELECT `id` FROM `reported_sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
        $query = $db->query($sql);
        if (!$query->num_rows)
        {
            $sql = "SELECT * FROM `sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);

            // create report
            $sql = "INSERT INTO `reported_sessions` SET ";
            $sql .= "sessionID = '"    . $query->row['sessionID']     . "', ";
            $sql .= "guid = '"         . $query->row['guid']          . "', ";
            $sql .= "motherboard = '"  . $query->row['motherboard']   . "', ";
            $sql .= "username = '"     . $query->row['username']      . "', ";
            $sql .= "dateIssue = '"    . $query->row['dateIssue']     . "'";
            $db->query($sql);
        }
    }

    // DEBUGGER
    elseif($mode[0] == "mode" && $mode[1] == "debugger")
    {
        $method = explode("=",    $req_parts[1])[1];
        $sessionID = explode("=", $req_parts[2])[1];
        $username = explode("=",  $req_parts[3])[1];
        $time = explode("=",      $req_parts[4])[1];

        // try to get userid
        $sql = "SELECT `id` FROM `users` WHERE `lastSessionID` = '" . $sessionID . "' OR `username` = '" . $username . "'";
		$query = $db->query($sql);
        if ($query->num_rows)
        {
            $userid = $query->row['id'];

            $sql = "SELECT `id` FROM `detected_debugger` WHERE `method` = '" . $method . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_debugger` SET ";
                $sql .= "method = '"        . $method .       "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "userid = '"        . $userid .       "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }
        else
        {
            $sql = "SELECT `id` FROM `detected_debugger` WHERE `method` = '" . $method . "' AND `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);
            if (!$query->num_rows)
            {
                // create report
                $sql = "INSERT INTO `detected_debugger` SET ";
                $sql .= "method = '"        . $method .         "', ";
                $sql .= "sessionID = '"     . $sessionID .    "', ";
                $sql .= "username = '"      . $username .     "', ";
                $sql .= "date = NOW()";
                $db->query($sql);
            }
        }

        $sql = "SELECT `id` FROM `reported_sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
        $query = $db->query($sql);
        if (!$query->num_rows)
        {
            $sql = "SELECT * FROM `sessions` WHERE `sessionID` = '" . $sessionID . "' LIMIT 1";
            $query = $db->query($sql);

            // create report
            $sql = "INSERT INTO `reported_sessions` SET ";
            $sql .= "sessionID = '"    . $query->row['sessionID']     . "', ";
            $sql .= "guid = '"         . $query->row['guid']          . "', ";
            $sql .= "motherboard = '"  . $query->row['motherboard']   . "', ";
            $sql .= "username = '"     . $query->row['username']      . "', ";
            $sql .= "dateIssue = '"    . $query->row['dateIssue']     . "'";
            $db->query($sql);
        }
    }

    // ERROR
    elseif($mode[0] == "mode" && $mode[1] == "error")
    {
        $error = explode("=",     $req_parts[1])[1];
        $call = explode("=",      $req_parts[2])[1];
        $ret = explode("=",       $req_parts[3])[1];
        $where = explode("=",     $req_parts[4])[1];

        $username = explode("=",  $req_parts[5])[1];
        $time = explode("=",      $req_parts[6])[1];
    
        // create report
        $sql = "INSERT INTO `runtime_errors` SET ";
        $sql .= "error = '"     . $error    .    "', ";
        $sql .= "callfunc = '"  . $call     .    "', ";
        $sql .= "ret = '"       . $ret      .    "', ";
        $sql .= "wherefunc = '" . $where    .    "', ";
        $sql .= "username = '"  . $username .    "', ";
        $sql .= "date = NOW()";
        $db->query($sql);
    }
    else header("HTTP/1.1 500 Internal Server Error");
}
else header("HTTP/1.1 500 Internal Server Error");
?>