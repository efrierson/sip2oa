<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $post = file_get_contents('php://input');
    $json_data = json_decode($post);

    //echo var_export($json_data,TRUE);
    
    $custid = $json_data->custid;
    $filename = "../conf/".$custid.".json";
    $filewrite = file_put_contents($filename, $post);
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = str_replace("includes/config.php","login.php?organization=".$custid,$actual_link);
    
    echo "<strong>URL for OpenAthens API Connector: </strong>".$actual_link."<br/><br />";
    echo "<strong>File Created / Updated:</strong> <br/><br/>".file_get_contents($filename);
?>