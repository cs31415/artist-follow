<?php header('Access-Control-Allow-Origin: *'); ?>

<?php
    header("Expires: 0");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    require 'common.php';

    function random() {
        return (float)rand()/(float)getrandmax();
    }

    $client_id = config()["client_id"]; 
    $redirect_uri = config()["redirect_url"]; // Your redirect uri

    $state = generateRandomString(16);

    $scope = "user-follow-modify";

    $url = "https://accounts.spotify.com/authorize";
    $url .= "?response_type=code";
    $url .= "&client_id=" . urlencode($client_id);
    $url .= "&scope=" . urlencode($scope);
    $url .= "&redirect_uri=" . urlencode($redirect_uri);
    $url .= "&state=" . urlencode($state);
    
    header("Location: " . $url);
?>
