<?php

include_once "profile.php";

function LoadProfileEdit($user)
{
   echo "<H1>Profile [".$user->username."]</H1>";
?>
<style>
  .apicontainer {
    width: 80%;
    margin: 20px auto;
  }
  .token {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
  }
  .token-header {
    font-weight: bold;
    color: #333;
  }
  .token-body {
    color: #666;
  }

  .icon {
    width: 24px; /* Adjust the width as needed */
    height: 24px; /* Adjust the height as needed */
    /* You can add additional styles here, such as color, margin, padding, etc. */
  }

  #editIcon {
    cursor: pointer; /* Change the cursor to a pointer (hand icon) on hover */
  }
</style>
  <div class="apicontainer" id="tokenContainer">
    <!-- Tokens will be dynamically added here -->
   <script>

  function convertTime(seconds) {
    var date = new Date(seconds * 1000); // JavaScript uses milliseconds, so multiply by 1000
    return date.toDateString() + ', ' + date.toLocaleTimeString();
  }

  function deleteToken(tokenid) {
       var myform = document.getElementById('api_mod');

       tokenDeleteInput = document.createElement('input');
       tokenDeleteInput.type = 'hidden';
       tokenDeleteInput.name = 'token_delete';
       tokenDeleteInput.value = tokenid;
       myform.appendChild(tokenDeleteInput);
       myform.submit();
  }
  function modify_token_name(tokenid, name) {
       var myform = document.getElementById('api_mod');

       t = document.createElement('input');
       t.type = 'hidden';
       t.name = 'token_mod';
       t.value = tokenid;
       myform.appendChild(t);
       t = document.createElement('input');
       t.type = 'hidden';
       t.name = 'token_name';
       t.value = name;
       myform.appendChild(t);
       myform.submit();
  }

  // Function to display tokens
  function displayTokens() {
    var container = document.getElementById('tokenContainer');
    
    jwtTokens.forEach(jwt => {
       try {
          var div = document.createElement('div');
          div.classList.add('token');
	  
	  
	  var payloadDiv = document.createElement('div');
              payloadDiv.classList.add('token-header');

          var friendlyName = jwt.getFriendlyName() != "" ?
                   jwt.getFriendlyName() :
                   ("https://" + jwt.getAudience() + ".obsec.io");

          payloadDiv.innerHTML = "Customer: <span id=\"editIcon_" + jwt.getId() + "\" >&#9998;</span><span id=\"customerText_"+jwt.getId()+"\">"+friendlyName+"</span>"; // Edit icon (pencil)

          div.appendChild(payloadDiv);
	  container.appendChild(div);

          document.getElementById("editIcon_"+jwt.getId()).addEventListener('click', function() {
             var customerText = document.getElementById("customerText_"+jwt.getId()).textContent;
             payloadDiv.innerHTML = "Customer: <span id=\"checkIcon_"+jwt.getId()+"\">&#10003;</span><input type=\"text\" id=\"customerInput_"+jwt.getId()+"\" value=\""+customerText+"\">"; // Checkmark icon
	  //});

             document.getElementById("checkIcon_"+jwt.getId()).addEventListener('click', function() {
               var newCustomerText = document.getElementById("customerInput_"+jwt.getId()).value;
               // Here you can handle the submission logic, e.g., sending the newCustomerText to the server
	       modify_token_name(jwt.getId(), newCustomerText);
	       // Submit this edit to the main page.

             });
          });
	      
	  // Token body
          var payloadBodyDiv = document.createElement('div');
              payloadBodyDiv.classList.add('token-body');
          var creatorNode = document.createTextNode("Creator: " + jwt.getName());
	  payloadBodyDiv.appendChild(creatorNode);
          payloadBodyDiv.appendChild(document.createElement('br')); // Line break between lines

	  var APIEndpoint = document.createTextNode("API Endpoint: " + jwt.getApiEndpoint());
	  payloadBodyDiv.appendChild(APIEndpoint);
          payloadBodyDiv.appendChild(document.createElement('br')); // Line break between lines


          var expiryNode = document.createTextNode("Expires: " + ((jwt.getExpiryDateEpoch() > 4873378319) ? "Never" : jwt.getExpiryDate()));
          payloadBodyDiv.appendChild(expiryNode);
          payloadBodyDiv.appendChild(document.createElement('br'));
	      
	  var icon1 = document.createElement('img');
              icon1.src = 'img/trash.png';
              icon1.alt = 'Icon1';
              icon1.classList.add('icon');

	      icon1.addEventListener('click', function(event) {
                 // Call a function or execute code when the icon is clicked
                 deleteToken(jwt.getId());
          });
          payloadBodyDiv.appendChild(icon1);

	  div.appendChild(payloadBodyDiv);
	      
	  container.appendChild(div);
       } catch (error) 
         { 
           console.error('Error parsing JWT token:', error);
         }
    });
    
  }

  // Display tokens
  displayTokens();

  function validateToken(token) {
            // Regular expression for JWT token format
            var jwtRegex = /^[A-Za-z0-9-_]+\.[A-Za-z0-9-_]+\.[A-Za-z0-9-_.+/=]*$/;

            return jwtRegex.test(token);
        }

  function validateForm() {
       var tokenInput = document.getElementById('tokenInput');
       var token = tokenInput.value.trim();

       if (!validateToken(token)) {
             alert('Invalid JWT token format. Please enter a valid token.');
             return false; // Prevent form submission
       }

       return true; // Allow form submission
   }

</script>
  <form method=POST action=index.php onsubmit="return validateForm()">
     <input type=hidden name=token_mod>
     <textarea name="token_add" id="tokenInput" placeholder="Enter new JWT token here..." rows="4" cols=120></textarea>       
     <button type="submit">Submit</button>
  </form>
  <form method=POST id=api_mod action=index.php>
      <input type=hidden name=token_mod>
  </form>
  </div>
<?php

}

?>
