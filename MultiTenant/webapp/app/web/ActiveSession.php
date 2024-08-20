<?php

include "home/LoadHomeReporting.php";
include "LoadProfileEdit.php";

function LoadActiveSession($user)
{

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obsidian MSSP</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }
 
        .logo {
            width: 200px; /* Adjust the width as needed */
            margin-bottom: 20px; /* Add some bottom margin */
        }

        .menu {
            width: 200px;
            background-color: #eeeeee;
            color: black;
            display: flex;
            flex-direction: column;
            padding: 20px;
	}

        .menu .menu-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .menu a {
            color: black;
            text-decoration: none;
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .menu a:hover {
            background-color: #e0e0e0;
        }

        .content {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
        }

        .logout {
            margin-top: auto;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="menu">
          <div class="menu-items">
            <img src="img/obsidian-logo.png" alt="Logo" class="logo">  
            <a onclick="showContent('home')">Home</a>
            <a onclick="showContent('profile')">Profile</a>
          </div>
          <div class="logout">
              <form id="logout-form" method=POST target=index.php>
              <input type=hidden name=logout value=logout>
              <a onclick="document.getElementById('logout-form').submit()">Logout</a>
              </form>
          </div>
        </nav>
        <div class="content">
	<div id="home" class="content-section <?php echo isset($_POST["token_mod"]) ? "hidden" : "" ?>">
<?php
	LoadHomeReport($user);
?>
            </div>
            <div id="profile" class="content-section <?php echo isset($_POST["token_mod"]) ? "" : "hidden" ?>">
<?php
		LoadProfileEdit($user);
?>
            </div>
        </div>
    </div>
    <script>
        function showContent(sectionId) {
            // Hide all sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.add('hidden');
            });

            // Show the selected section
            document.getElementById(sectionId).classList.remove('hidden');
        }
    </script>
</body>
</html>
<?php
}
?>
