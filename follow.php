<?php header('Access-Control-Allow-Origin: *'); ?>

<?php
    function console_log($data) {
        return;
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    function http_request($method, $url, $access_token) {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($method == "PUT") {
            curl_setopt($curl, CURLOPT_PUT, true);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'authorization: Bearer ' . $access_token
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        
        curl_close($curl);

        return $result;
    }

    $access_token = $_GET["token"];
    $spotify_id = $_GET["id"];
    $artist_ids = "04sSlzheDeFzbqU23DVGTd,00DkRCKgM6Ku90WtOfoYlw";

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
        $result = http_request("PUT", $url, $access_token);
        if ($result !== FALSE) {
            $url = $api_root_url . "/artists?ids=" . $artist_ids_batch;

            $result = http_request("GET", $url, $access_token);
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
        mail("cs31415@yahoo.com", $spotify_id . "followed all artists on Auricle collective", "");
    }
    else {
        mail("cs31415@yahoo.com", $spotify_id . "Failed to follow all artists on Auricle collective", "");
    }

    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($result_json));
?>
