<?php

include "ActiveSession.php";
include "Login.php";
include_once "profile.php";

# To be replaced with supporting profiles
$server = "database";

$db = getenv('MYSQL_DATABASE');
$user = getenv('MYSQL_USER');
$pass = getenv('MYSQL_PASSWORD');


$google_client_id = getenv("GOOGLE_CLIENT_ID");
$callback_url = "http://127.0.0.1:5000/";
$google_auth_url = "https://accounts.google.com/o/oauth2/v2/auth?";
$client_secret = getenv("GOOGLE_CLIENT_SECRET");


function InitSession($uid)
{
    global $conn;
    $query = "INSERT INTO active_sessions (userid, userip, expires) VALUES(
                 $uid,
                 '".$_SERVER["REMOTE_ADDR"]."',
                 ".(time() + 3600).")";
       if ($conn->query($query))
       {
          $sid = $conn->insert_id;
          setcookie("session_id", $sid, time() + 3600, "/");
          $_COOKIE['session_id'] = $sid;
       }
       else
       {
          error_log("Valid username/password but failed to create session");
       }
}


$conn = new mysqli($server, $user, $pass, $db);
if ($conn->connect_error) 
{
   die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["logout"]))
{
   setcookie("session_id", 0, time() - 3600, "/");
   $_COOKIE['session_id'] = 0;
}
else if (isset($_POST["login"]) && $_POST["login"] == "loginup")
{
   error_log("Login detected with username=".$_POST["username"]." and pass=".$_POST["password"]);
   $query = "SELECT * FROM users WHERE username='".$_POST["username"]."' 
	                         AND password='".$_POST["password"]."'";

   if (($result = $conn->query($query)) && $result->num_rows > 0)
   {
       error_log("username/password match");
       $row = $result->fetch_assoc();
       InitSession($row["userid"]);
   }
   else
   {
       error_log("username/password incorrect");
   }
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

      // We always call the insert - worst it fails, who cares.
      $query = "INSERT INTO users (username, sso) VALUES(
	                      '$email', true)";
      try {
         $result = $conn->query($query);
      } catch (Exception $e) {}
    
      $query = "SELECT * FROM users WHERE username='".$email."' 
	                         AND sso=true";
      $result = $conn->query($query);
    
      $row = $result->fetch_assoc();
      InitSession($row["userid"]);

  }
}


$profile = new UserProfile();

### We have successfully connected
## Check if there is an active session.
if (isset($_COOKIE['session_id']))
{
   ## Check if there is an entry in the session table.
   $clientip = $_SERVER["REMOTE_ADDR"];

   $query = "SELECT * FROM active_sessions 
	        INNER JOIN users on active_sessions.userid = users.userid
		WHERE active_sessions.session_id='".$_COOKIE['session_id']."' 
   	                                   AND active_sessions.userip='".$clientip."'
 	                                   AND active_sessions.expires > ".time();

   $result = $conn->query($query);
   if ($result && $result->num_rows > 0)
   {
      $row = $result->fetch_assoc();
      $profile->Set($row["userid"], $row["username"]);
      error_log("Active Session Found ".$row["userid"]);
   }
   else
   {
     error_log("Expired or invalid IP");
     # Expired or invalid IP.
     setcookie("session_id", 0, time() - 3600, "/");
   }
}
else {
   error_log("No session cookie set");
}

########
# Base layout
#
if ($profile->userid != -1)
{
    # If there was a JWT addition, we handle it here (only if still authenticated).
    if (isset($_POST["token_mod"]) && isset($_POST["token_add"]))
    {
	$query = "INSERT INTO apitoken_data (userid, token) VALUES (
		 '".$profile->userid."', '".$_POST["token_add"]."');";
	try {
		$conn->query($query);
	} catch (Exception $e) { }
    }
    else if (isset($_POST["token_mod"]) && isset($_POST["token_delete"]))
    {
	    $query = "DELETE FROM apitoken_data WHERE token_id='".$_POST["token_delete"]."'";
       try {
		$conn->query($query);
	} catch (Exception $e) { }
    }
    else if (isset($_POST["token_mod"]) && isset($_POST["token_name"]))
    {
	    $query = "UPDATE apitoken_data set token_name='".$_POST["token_name"]."' WHERE
		          token_id='".$_POST["token_mod"]."';";
            try {
		  $conn->query($query);
	    } catch (Exception $e) { }

    }
    echo "<script src=\"scripts.js\"></script>
	  <script>
             let jwtTokens = [];";
    $query = "SELECT * FROM apitoken_data WHERE userid='".$profile->userid."'";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {
	    $t = new jwtToken($row["token_id"],
		          $row["token"], 
		          $row["token_name"], 
			  $row["api_endpoint"]);
	$profile->AddToken($t);
	echo "
	   token = new JwtToken(".$row["token_id"].",
                               '".$row["token"]."', 
                                    '".$row["token_name"]."',
                                    '".$row["api_endpoint"]."');
	   jwtTokens.push(token);
             ";
    }
    echo "</script>";
    LoadActiveSession($profile);
}
else
{   
    LoadLoginScreen();
}
?>
