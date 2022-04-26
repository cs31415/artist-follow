<?php
    function random() {
        return (float)rand()/(float)getrandmax();
    }
    /**
     * Generates a random string containing numbers and letters
     * @param  {number} length The length of the string
     * @return {string} The generated string
     */
    function generateRandomString($length) {
        $text = "";
        $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for ($i = 0; $i < $length; $i++) {
            $rand = random();
            $len = strlen($possible);
            $idx = floor(random() * strlen($possible)); 
            $text .= $possible[$idx];
        }
        return $text;
    };

    $client_id = "<client_id from Spotify app developer dashboard>"; 
    $redirect_uri = "http://localhost/follow/"; // Your redirect uri

    $state = generateRandomString(16);

    $scope = "user-follow-modify";

    $url = "https://accounts.spotify.com/authorize";
    $url .= "?response_type=token";
    $url .= "&client_id=" . urlencode($client_id);
    $url .= "&scope=" . urlencode($scope);
    $url .= "&redirect_uri=" . urlencode($redirect_uri);
    $url .= "&state=" . urlencode($state);
    
    header("Location: " . $url);
?>
