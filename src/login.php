<?php header('Access-Control-Allow-Origin: *'); ?>

<?php

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
