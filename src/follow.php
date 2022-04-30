<?php header('Access-Control-Allow-Origin: *'); ?>

<?php
    require_once 'common.php';

    $access_token = $_GET["access_token"];
    $spotify_id = $_GET["id"];
    $artist_ids = config()["artist_ids"];

    $api_root_url = "https://api.spotify.com/v1";
    $url = $api_root_url . "/me/following?type=artist&ids=" . $artist_ids;
    
    $startIdx = 0;
    $more = True;
    $artist_ids_arr = str_getcsv($artist_ids);
    $batch_size = 50;
    $result_json = [];
    $followed = FALSE;
    while ($more) {
        $artist_ids_batch = join(",", array_slice($artist_ids_arr, $startIdx, $batch_size));
        $result = http_request("PUT", $url, array('authorization: Bearer ' . $access_token));
        if ($result !== FALSE) {
            $url = $api_root_url . "/artists?ids=" . $artist_ids_batch;

            $result = http_request("GET", $url, array('authorization: Bearer ' . $access_token));
            $result_json = array_merge($result_json, json_decode($result,true)["artists"]);

            $followed = True;
        }
        else {
            $followed = False;
            $result_json = json_decode("{\"name\": \"Error following artists\",\"images\":[{\"url\":\"\"},{\"url\":\"\"},{\"url\":\"\"}]}");
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
    echo(json_encode($result_json));
?>
