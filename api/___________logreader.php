<?php
/*
RockAnticheat - multicomplex SA:MP anti-cheat system
with innovative technologies and infallible verdicts.

Developed by savvin & 0Z0SK0

(c) 2023
*/

$aes_key = '68331F74E7F96F238FCB5517F3E7D298A704FFA75B1ABCB4850A6DAC2632A48B';
$aes_iv = '8D85DDD410391A3353C181285BFDC1C9';

if(isset($_FILES['userfile']))
{
    $handle = fopen($_FILES['userfile']['tmp_name'], "r");
    if ($handle) {
        $data = "";

        while (($line = fgets($handle)) !== false) {
            $decrypted = openssl_decrypt($line, 'aes-256-cbc', hex2bin($aes_key), 0, hex2bin($aes_iv));
            $data = $data . $decrypted . "\n";
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=output.txt');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . strlen($data));
        
        echo $data;

        fclose($handle);
    }
}
?>

<form enctype="multipart/form-data" action="___________logreader.php" method="POST">
    <input style="width:100%;" name="userfile" type="file" />
    <input type="submit" value="Отправить файл" />
</form>