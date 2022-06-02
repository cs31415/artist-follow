<?php header('Access-Control-Allow-Origin: *'); ?>

<?php
    require_once 'common.php';

    // get token & state from url 
    $error = isset($_GET["error"]) ? $_GET["error"] : NULL;
    $code = $_GET["code"];
    $state = $_GET["state"];
    $url = "/follow";    

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

        $result = http_request("POST", $url, 
            array(
                'authorization: Basic ' . base64_encode($client_id . ":" . $client_secret)
            ),
            $fields_string);

        if ($result !== FALSE && ($result === "" || !array_key_exists("error", json_decode($result,true)))) {    
            $result_arr = json_decode($result, true);
            $access_token = $result_arr["access_token"];
            $enc_access_token = encrypt($access_token); 

            setcookie("t", $enc_access_token, time()+1200); 
            setcookie("state", $state, time()+1200); 
            header("Location: /follow");

            // get the client-id & name
            $url = 'https://api.spotify.com/v1/me';   
            $result = http_request("GET", $url, 
                array(
                    'authorization: Bearer ' . $access_token
                ));

            if ($result !== FALSE && ($result === "" || !array_key_exists("error", json_decode($result,true)))) {    
                $result_arr = json_decode($result, true);
                $display_name = $result_arr["display_name"];
                $user_id = $result_arr["id"];
                setcookie("display_name", $display_name);
                setcookie("user_id", $user_id);
                header("Location: /follow");
            }
            else {
                header("Location: /follow?error=profile_error");
            }    
        }
        else {
            header("Location: /follow?error=authorization_error");
        }
    }
?>
