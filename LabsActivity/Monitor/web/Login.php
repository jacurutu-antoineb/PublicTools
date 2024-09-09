<?php

function LoadLoginScreen()
{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obsidian Multi-Tenant MSSP Login</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f0f2f5;
        }

        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container input[type="text"], .login-container input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .login-container button[type="submit"] {
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button[type="submit"]:hover {
            background: #0056b3;
        }

        .separator {
            margin: 20px 0;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .separator::before, .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #ccc;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }

        .google-button {
            background: #84b6f7;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .google-button img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .google-button:hover {
            background: #c23321;
        }

        .logo {
            width: 200px; /* Adjust as needed */
            margin: 0 auto 20px; /* Centers the logo and adds some bottom margin */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="img/obsidian-logo.png" alt="Logo" class="logo">
        <h1>Login</h1>
        <button class="google-button" onclick="handleGoogleLogin()">
            <img src="https://www.google.com/favicon.ico" alt="Google Logo">
            Sign in with Google
        </button>
    </div>

    <script>
        function handleGoogleLogin() {
	    // Redirect to Google SSO
<?php
	    global $google_client_id;
	    global $google_auth_url;
	    global $callback_url;
            echo "window.location.href = '".$google_auth_url;
	    echo "client_id=".$google_client_id;
	    echo "&redirect_uri=".$callback_url;
	    echo "&scope=openid profile email";
	    echo "&response_type=code';";
?>
        }
    </script>
</body>
</html>
<?php
}

?>
