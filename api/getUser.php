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
if($apiKey != $config->api_key)
{
    if(isset($_GET['action']))
    {
        if($_GET['action'] == "multi")
        {
            $sql = "SELECT * FROM `users` WHERE `username` = '" . $_GET['name'] . "'";
            $query = $db->query($sql);

            if ($query->num_rows)
            {
                $user_guid = $query->row['guid'];
                
                $sql = "SELECT `id`,`username` FROM `users` WHERE `guid` = '" . $user_guid . "'";
                $query = $db->query($sql);
                $rows = $query->rows;

                $multiaccs = array();
                foreach($rows as $row)
                {
                    $multiaccs[] = array(
                        'id' => $row['id'],
                        'name' => $row['username']
                    );
                }

                $response = array(
                    'status' =>  true,
                    'success'  => $multiaccs
                );
            }
            else
            {
                $response = array(
                    'status' =>  false,
                    'error'  => 'User [nick: ' . $_GET['name'] .  '] not found'
                );
            }
        }
        elseif($_GET['action'] == "bans")
        {
            $sql = "SELECT `username`, `ban_info`, `ban_date` FROM `users` WHERE `banned` = 1";
            $query = $db->query($sql);
            $rows = $query->rows;

            $bans = array();
            foreach($rows as $row)
            {
                $bans[] = array(
                    'username' => $row['username'],
                    'reason' => $row['ban_info'],
                    'date' => $row['ban_date'],
                );
            }

            $response = array(
                'status' =>  true,
                'success'  => $bans
            );
        }
        else
        {
            $response = array(
                'status' =>  false,
                'error'  => 'Wrong action'
            );
        }
    }
    else
    {
        $response = array(
            'status' =>  false,
            'error'  => 'Wrong Arguments'
        );
    }
}
else
{
    $response = array(
        'status' =>  false,
        'error'  => 'Wrong API key'
    );
}

die(json_encode($response));
?>