<?php

include "ActiveSession.php";
include "Login.php";
include "LoadMon.php";

$google_client_id = getenv("GOOGLE_CLIENT_ID");
$callback_url = "http://labmon.com:5000/";
$google_auth_url = "https://accounts.google.com/o/oauth2/v2/auth?";
$client_secret = getenv("GOOGLE_CLIENT_SECRET");

if (isset($_COOKIE["session_id"]))
{
    //FIXME
    echo LoadMonitor();
}
else if (isset($_GET["code"]) && isset($_GET["scope"]))
{
   $url = 'https://oauth2.googleapis.com/token';

   $data = array(
    'grant_type' => 'authorization_code',
    'client_id' => $google_client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $callback_url,
    'code' => $_GET["code"]
   );

   // Build the POST data as a query string
   $postdata = http_build_query($data);

   // Create a stream context with the required options
   $options = array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => $postdata
     )
   );

   $context = stream_context_create($options);

   // Perform the request
   $response = file_get_contents($url, false, $context);

   // Check for errors
   if ($response === false) {
	   echo "Error logging in";
           // Do Nothing
   } else {
      $response_array = json_decode($response, true);
      $id_token = $response_array['id_token'];
      //unpack the jwt token
      // Split the JWT token into parts
      $token_parts = explode('.', $id_token);

      // Extract the payload (claims) from base64 URL encoding
      $payload_base64 = $token_parts[1];
      $payload = base64_decode(strtr($payload_base64, '-_', '+/'));

      // Parse the JSON payload to access the claims
      $claims = json_decode($payload, true);

      // Access specific claims
      $email = $claims['email'];

      // FIXME
      setcookie("session_id", "connected", time() + 3600, "/");

      echo LoadMonitor();
   }
}
else
{
  LoadLoginScreen();
}


?>


