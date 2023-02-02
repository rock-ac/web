<?php
/*
RockAnticheat - multicomplex SA:MP anti-cheat system
with innovative technologies and infallible verdicts.

Developed by savvin & 0Z0SK0

(c) 2023
*/

require_once('../../../engine/main/config.php');
require_once('../../../engine/main/db.php');

$config = new Config();
$db = new DB($config->db_type, $config->db_hostname, $config->db_username, $config->db_password, $config->db_database);

if($_SERVER['HTTP_USER_AGENT'] == "STALKER/1.0")
{
    $sql = "SELECT `pattern` FROM `cmemoryfinder`";
    $query = $db->query($sql);
    $rows = $query->rows;

    $res = "";

    foreach ($rows as $key => $element) {
        if ($key === array_key_last($rows)) {
            $res .= $element['pattern'];
        }
        else $res .= $element['pattern'] . '^';
    }

    // encrypt AES
    $encrypted = openssl_encrypt($res, 'aes-256-cbc', hex2bin($config->aes_key), 0, hex2bin($config->aes_iv));
    echo $encrypted;
}