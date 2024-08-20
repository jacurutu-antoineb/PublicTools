<?php

include "LoadThreat.php";
include "LoadPosture.php";
include "LoadIRM.php";

function LoadData()
{
}

function LoadHomeReport($profile)
{
   global $conn;
?>
<style>
.bubble {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .bubble h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .bubble p {
            color: #666;
            line-height: 1.6;
	}
.bubble table {
            width: 100%;
            border-collapse: collapse;
        }

        .bubble th, .bubble td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        .bubble th {
            cursor: pointer;
            background: #f0f2f5;
        }

        .bubble th:hover {
            background: #e0e0e0;
</style>
<?php

   // Now we load historical data (if it exists).
   $ids = [];
   foreach ($profile->jwtToken as $token)
   {
	   array_push($ids, $token->tokenid);
   }
   $query = "SELECT * FROM tenant_data WHERE token_id IN (" . implode(',', $ids) . ")";

   try {
          $results = $conn->query($query);
	  if ($results->num_rows < (count($ids)))
	  {
            // CACHE MISS on at least 1.

	    
	  }
	  else
	  {
	      
	      echo "Query success, all rows have data";
	  }
   } catch (Exception $e) 
   {
	
   }

	LoadPosture($profile);
//	LoadThreat($profile);
//	LoadIRM($profile);
?>
<script src="queries.js?v=1.002"></script>
<script>
   function RunGQL(token, endpoint, query) {
        var auth = 'Bearer ' + token;
        return fetch(endpoint, {
	   method: 'POST',
           headers: {
	      'Content-Type': 'application/json',
	      'Authorization': auth
           },
           body: JSON.stringify(query),
	})
	.then(response => response.json())
	.then(data => { return data;})
        .catch(error => {
	      return null;
	});
   }

   function loadTable(name) {
     console.error(name);
     var table = document.getElementById(name);
     var tbody = table.getElementsByTagName('tbody')[0];

     // Destroy the body if it has stuff
     tbody.innerHTML = '';
     // jwttokens should already be declared
     // Create one row for each token we manage
     jwtTokens.forEach(jwt => {
	var row = document.createElement('tr');
	var col = document.createElement('td');
	col.textContent = (jwt.getFriendlyName() != "") ?
		                jwt.getFriendlyName() :
				jwt.getAudience();
	row.appendChild(col);
	
	col = document.createElement('td');
        col.textContent = "NA";
	RunGQL(jwt.token, jwt.endpoint, q_listPostureScores)
	   .then(result => {
	       console.log("RES-1:"+JSON.stringify(result.data.listPostureScores.scores[0]));
  	       col.textContent = result.data.listPostureScores.scores[0].score.value;
           })
           .catch(error => {
               console.error("An error occurred:", error);
           });
	console.log("RES-TEST");
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 7;
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 9;
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 12;
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 15;
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 19;
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 22;
	row.appendChild(col);
	
	col = document.createElement('td');
	col.textContent = 25;
	row.appendChild(col);

	tbody.appendChild(row);
     });
   }

   //document.addEventListener('DOMContentLoaded', loadTable('table2'));
   //document.addEventListener('DOMContentLoaded', loadTable('table3'));
   
   function sortTable(n, tableId) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById(tableId);
            switching = true;
            // Set the sorting direction to ascending:
            dir = "asc"; 
            /* Make a loop that will continue until
            no switching has been done: */
            while (switching) {
                // Start by saying: no switching is done:
                switching = false;
                rows = table.rows;
                /* Loop through all table rows (except the
                first, which contains table headers): */
                for (i = 1; i < (rows.length - 1); i++) {
                    // Start by saying there should be no switching:
                    shouldSwitch = false;
                    /* Get the two elements you want to compare,
                    one from current row and one from the next: */
                    x = rows[i].getElementsByTagName("TD")[n];
                    y = rows[i + 1].getElementsByTagName("TD")[n];
                    /* Check if the two rows should switch place,
                    based on the direction, asc or desc: */
                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    /* If a switch has been marked, make the switch
                    and mark that a switch has been done: */
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    // Each time a switch is done, increase this count by 1:
                    switchcount ++;
                } else {
                    /* If no switching has been done AND the direction is "asc",
                    set the direction to "desc" and run the while loop again. */
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>

<?php
} 

?>


