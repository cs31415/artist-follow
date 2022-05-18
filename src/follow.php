<?php header('Access-Control-Allow-Origin: *'); ?>

<?php
    require_once 'common.php';

    $access_token = decrypt(str_replace(" ", "+", $_GET["access_token"]));

    $spotify_id = $_GET["id"];
    $artist_ids = config()["artist_ids"];

    $api_root_url = "https://api.spotify.com/v1";
    
    $startIdx = 0;
    $more = True;
    $artist_ids_arr = str_getcsv($artist_ids);
    $batch_size = 50;
    $result_json = [];
    $followed = FALSE;
    while ($more) {
        $artist_ids_batch = join(",", array_slice($artist_ids_arr, $startIdx, $batch_size));
        $url = $api_root_url . "/me/following?type=artist&ids=" . $artist_ids_batch;
        $result = http_request("PUT", $url, array('authorization: Bearer ' . $access_token));
        if ($result !== FALSE && ($result === "" || !array_key_exists("error", json_decode($result,true)))) {
            $url = $api_root_url . "/artists?ids=" . $artist_ids_batch;

            $result = http_request("GET", $url, array('authorization: Bearer ' . $access_token));
            $result_json = array_merge($result_json, json_decode($result,true)["artists"]);

            $followed = True;
        }
        else {
            $followed = False;
            $result_json = json_decode("{\"msg\": \"" . "Follow artists failed with error: " . json_decode($result,true)["error"]["message"] . "\"}");
            break;
        }
        $startIdx = $startIdx + $batch_size;
        if (count($artist_ids_arr)-1 < $startIdx) {
            $more = False;
        } 
    }


    if ($followed) {
        mail(config()["admin_email"], $spotify_id . "followed all artists on Auricle collective", "");
    }
    else {
        mail(config()["admin_email"], $spotify_id . "Failed to follow all artists on Auricle collective", "");
    }

    header('Content-Type: application/json; charset=utf-8');
    http_response_code($followed ? 200 : 500);
    echo(json_encode($result_json));
?>
