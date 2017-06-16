<?php
set_time_limit(10);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class MyEncryption
{

    public $pubkey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDLa7lVQ9kYoqrrqPIUv2dhDvyg
hraW4lgquGOLM59+G03F65uSXtom+lOVt/Wam2ROtrdW/JOpIIk7KUuk+byBBO1a
e0YZof7Q5YHIRGvMbLC2Z+fbTd/a0fp4SY3HZH5GDv8dcxJR8ZhSMBhy0x+VaLdO
M68I/cdG7IQrXDXXYQIDAQAB
-----END PUBLIC KEY-----';
    public $privkey = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDLa7lVQ9kYoqrrqPIUv2dhDvyghraW4lgquGOLM59+G03F65uS
Xtom+lOVt/Wam2ROtrdW/JOpIIk7KUuk+byBBO1ae0YZof7Q5YHIRGvMbLC2Z+fb
Td/a0fp4SY3HZH5GDv8dcxJR8ZhSMBhy0x+VaLdOM68I/cdG7IQrXDXXYQIDAQAB
AoGAMMGVHlawxjLW/Lz1qPtnb+ADtQYU5X1C3JptYYPyCmvI7FNYanDJoOYG+q+o
8nGkTSmGMBdB3RurSL7RHq2s/GGvDm5Z//mY06ewhIEj24nuN6O44KWug35WK1bX
83AeWc4Ncu8Kwf83Ok9RsLOKqzozkvCqrzIPiv3h3N587TECQQD6jUmzhXa2bAgZ
mPETDAwOEtoxncvUThzBrooHvOpQVTwYudGM4FJtBVkst40i3i0MuKeCGJbCYt3O
Wr7HFzaDAkEAz9gT854+VswhguTtEdD5k7e3Bbbr1u9FQNX5phbXFIW1FBMVlpIz
gdDxeZFEhPrTxx73dbeQmSfVDHtB8VV1SwJBAMaIEfBYPvrJm5l84PlwwFSeh5pt
KMfvpUWrYeBDx38kKtyE0RDJ50ZPyJtwTjtkxVmhL8ocZcldwdfze9wR/rUCQHHs
TDNSX3UP+qZWeKM1WjdfkZAuTWLIT7tUDby99DIpf7F7LHAVvum+7zzlJRuGqKIS
FS2O6lEohhyLSv/PCbUCQHWoaM6cYgYVGIugtI/bQTPLFnK0JXZOW6KINj8IQil4
ktYD1mqvPPOoThl5q08dsUDdM9qWy4wFttJRVxlc7qw=
-----END RSA PRIVATE KEY-----';

    public function encrypt($data)
    {
        if (openssl_public_encrypt($data, $encrypted, $this->pubkey))
            $data = base64_encode($encrypted);
        else
            throw new Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');

        return $data;
    }

    public function decrypt($data)
    {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey))
            $data = $decrypted;
        else
            $data = '';

        return $data;
    }
}

require_once("sip2.class.php");
$mysip = new sip2;

$post = file_get_contents('php://input');

//echo "POST: ".$post."<br/><br/>";
$json_data = json_decode($post);

$encrypted_un = $json_data->un;
$encrypted_pw = $json_data->pw;

$codex = new MyEncryption();

$mysip->debug = true;

$mysip->hostname = "192.69.191.116";
$mysip->port = 6001;

$mysip->patron = $codex->decrypt($encrypted_un);
$mysip->patronpwd = $codex->decrypt($encrypted_pw);

$result = $mysip->connect();

$loginmessage = $mysip->msgLogin($mysip->patron,$mysip->patronpwd);

$loginmessagedisplayed = str_replace($mysip->patron,"{PATRONUSERNAME}",$loginmessage);
$loginmessagedisplayed = str_replace($mysip->patronpwd,"{PATRONPASSWORD}",$loginmessagedisplayed);

echo "<br/><strong>Request Message: </strong>".$loginmessagedisplayed."<br/><em>Replaced {PATRONUSERNAME} and {PATRONPASSWORD} with values you put into the form above.</em><br/>";

$response = $mysip->get_message($loginmessage);

echo "<br /><strong>Login Status: </strong>";

if ($response[2] == "0") {
    echo "Failed.  Response (".$response.") contains ".$response[2]." in third position.";
} else if ($response[2] == "1") {
    echo "Succeeded.  Response (".$response.") contains ".$response[2]." in third position.";    
} else {
    echo "Unexpected Response.  Response (".$response.") contains neither 0 or 1 in third position (it contains ".$response[2].").";
}
echo "<br/>";

$statusmessage = $mysip->msgPatronStatusRequest();

$statusmessagedisplayed = str_replace($mysip->patron,"{PATRONUSERNAME}",$statusmessage);
$statusmessagedisplayed = str_replace($mysip->patronpwd,"{PATRONPASSWORD}",$statusmessagedisplayed);
echo "<br/><strong>Request Message: </strong>".$statusmessagedisplayed."<br/><em>Replaced {PATRONUSERNAME} and {PATRONPASSWORD} with values you put into the form above.</em><br/>";

$response = $mysip->get_message($statusmessage);

echo "<br /><strong>Patron Status: </strong>".$response;

?>
