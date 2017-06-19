<?php
session_start();
if ((!isset($_GET['organization'])) || (!isset($_GET['returnData']))) {
    die("Organization ID or returnData not set.");
}
?>
<html>
    <head>
        <title>SIP2</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="includes/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="includes/jsencrypt.min.js"></script>
        <script type="text/javascript" src="includes/login.js"></script>
        <link rel="stylesheet" href="includes/login.css" />
    </head>
    <body>
        <div id="login">
            <div id="warning"></div>
            <input type="hidden" id="custid" value="<?php echo $_GET['organization']; ?>" />
            <input type="hidden" id="returnData" value="<?php echo $_GET['returnData']; ?>" />
            <input type="text" id="login-un" placeholder="Barcode / Username" /><br />
            <input type="password" id="login-pw" placeholder="Password" /><br />
            <button onclick="sip2login();">Login</button>
        </div>
        <!--<div id="results">
            <strong>SIP2 Response will appear here.</strong>
        </div>-->

        <div style="display:none;">
            <label for="pubkey">Public Key</label><br/>
            <textarea id="pubkey" rows="15" style="width:100%" readonly="readonly">-----BEGIN PUBLIC KEY-----
THISISAFAKEPUBLICKEYTHISISAFAKEPUBLICKEYTHISISAFAKEPUBLICKEYTHIS
ISAFAKEPUBLICKEYTHISISAFAKEPUBLICKEYTHISISAFAKEPUBLICKEYTHISISAF
AKEPUBLICKEYTHISISAFAKEPUBLICKEYTHISISAFAKEPUBLICKEYTHISISAFAKEP
UBLICKEY
-----END PUBLIC KEY-----</textarea>
        </div>
    </body>
</html>