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
    if(isset($_GET['action']) && isset($_GET['name']))
    {
        if($_GET['action'] == "block")
        {
            $reason = iconv("CP1251", "UTF-8", $_GET['reason']);

            $sql = "SELECT * FROM `users` WHERE `username` = '" . $_GET['name'] . "'";
            $query = $db->query($sql);
            $user_id = $query->row['id'];

            if ($query->num_rows)
            {
                $sql = "UPDATE `users` SET `banned` = '1', `ban_info` = '" . $reason . "', `ban_date` = NOW() WHERE `id` = '" . $user_id . "'";
                $query = $db->query($sql);

                $response = array(
                    'status' =>  true,
                    'success'  => 'User #' . $user_id .  ' blocked (reason: ' . $reason . ')'
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
        elseif($_GET['action'] == "unblock")
        {
            $sql = "SELECT * FROM `users` WHERE `username` = '" . $_GET['name'] . "'";
            $query = $db->query($sql);
            $user_id = $query->row['id'];

            if ($query->num_rows)
            {
                $sql = "UPDATE `users` SET `banned` = '0', `ban_info` = NULL, `ban_date` = NULL WHERE `id` = '" . $user_id . "'";
                $query = $db->query($sql);

                $response = array(
                    'status' =>  true,
                    'success'  => 'User #' . $user_id .  ' unblocked'
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