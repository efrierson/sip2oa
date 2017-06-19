<?php
if ((isset($_GET["submit"])) && (isset($_GET["password"])) && ($_GET["password"] == "ebsco")) {
    if (isset($_GET["returnData"])) {
        $request_json = [];
        $request_json["connectionID"] = "2955";
        $request_json["uniqueUserIdentifier"] = "anony-mouse2-oaconnector";
        //$request_json["displayName"] = "Eric from OA Connector";
        $request_json["returnData"] = $_GET["returnData"];
        //$request_json["attributes"] = [];
        //$request_json["attributes"]["firstName"] = "Eric";
        //$request_json["attributes"]["lastName"] = "Frierson";
        //$request_json["attributes"]["emailAddress"] = "ericFake@ebsco.com";
        $data_string = json_encode($request_json);
    
        $url = "https://login.openathens.net/api/v1/oaebsco.com/organisation/69519194/local-auth/session";
    
        $session = curl_init($url); 	               // Open the Curl session
    
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($session, CURLOPT_POSTFIELDS, $data_string);                                                                  
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);                                                                      
        curl_setopt($session, CURLOPT_HTTPHEADER, array(
            'Authorization: OAApiKey 18dfaf90-befb-4d83-81ad-d31c59c8b863',
            'Content-type: application/vnd.eduserv.iam.auth.localAccountSessionRequest+json'
        ));
        
        $headers = array(
            'Authorization: OAApiKey 18dfaf90-befb-4d83-81ad-d31c59c8b863',
            'Content-type: application/vnd.eduserv.iam.auth.localAccountSessionRequest+json'
        );
    
        $html = curl_exec($session); 	                       // Make the call
        //header("Content-Type: text/xml"); 	               // Set the content type appropriately
        curl_close($session); // And close the session
    
        $redirect_url = json_decode($html);
    
        if (isset($_GET['verbose'])) {
            echo "Request URL: ".$url."<br/><br />";
            echo "Request Headers: ".var_export($headers,TRUE)."<br/><br/>";
            echo "Request JSON: <textarea>".$data_string."</textarea><hr />";
            echo "Redirect URL: <textarea>".$redirect_url->sessionInitiatorUrl."</textarea><hr />";
        } else {
            header("Location: ".$redirect_url->sessionInitiatorUrl);
        }
    }
} else {
    ?>

<form action="index.php" method="GET">
    <input type="hidden" name="returnData" value="<?php echo $_GET['returnData']; ?>" />
    <input type="checkbox" name="verbose" value="y" /> Verbose Response (no redirect)<br />
    <input type="password" name="password" placeholder="password" />
    <input type="submit" name="submit" value="go" />
</form>

    <?php
}

?>