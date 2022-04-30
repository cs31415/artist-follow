<?php header('Access-Control-Allow-Origin: *'); ?>

<?php
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header('Access-Control-Allow-Methods: GET, POST');
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    require_once 'common.php';

    // get token & state from url 
    $error = isset($_GET["error"]) ? $_GET["error"] : NULL;
    $code = $_GET["code"];
    $state = $_GET["state"];

    console_log("error = " . $error);
    console_log("code = " . $code);
    console_log("state = ". $state);

    $url = "http://localhost/follow";    

    if (!is_null($error)) {
        header("Location: " . $url . "?error=" . $error);    
    }
    elseif (is_null($state)) {
        header("Location: " . $url . "?error=state_mismatch");    
    }
    else {
        $client_id = config()["client_id"];
        $client_secret = config()["client_secret"];
        $redirect_uri = config()["redirect_url"];

        $url = "https://accounts.spotify.com/api/token";

        $fields = [
            "code"          => urlencode($code),
            "redirect_uri"  => $redirect_uri,
            "grant_type"    => "authorization_code",
            "state" => $state
        ];
        
        //url-ify the data for the POST
        $fields_string = http_build_query($fields);
        console_log("fields_string = " . $fields_string);

        $result = http_request("POST", $url, 
            array(
                'authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
            ),
            $fields_string);

        console_log("result = ". $result);
        if ($result !== FALSE) {    
            $result_arr = json_decode($result, true);
            $access_token = $result_arr["access_token"];
            $state = $result_arr["state"];
            setcookie("access_token", $access_token, time()+1200); 
            setcookie("state", $state, time()+1200); 
            setcookie("id", $client_id);
            header("Location: http://localhost/follow");
        }
        else {
            header("Location: http://localhost/follow?error=authorization_error");
        }
    }
?>
