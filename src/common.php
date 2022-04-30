<?php
    function console_log($data) {
        return;
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    function http_request($method, $url, $headers, $body = NULL) {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, $url);
        if ($method == "POST") {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }
        elseif ($method === "PUT") {
            curl_setopt($curl, CURLOPT_PUT, true);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        
        curl_close($curl);

        return $result;
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

    function config() {
        $ini = parse_ini_file('../follow.ini');
        return $ini;
    }
?>
