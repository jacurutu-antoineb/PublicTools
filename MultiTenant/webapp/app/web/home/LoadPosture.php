<?php
function LoadPosture($profile)
{
?>
<div id=posture_container class="bubble">
                <h2>Posture Summary</h2>
                <table id="table1">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0, 'table1')">Tenant Name</th>
                            <th onclick="sortTable(1, 'table1')">Score</th>
                            <th onclick="sortTable(2, 'table1')">Crit</th>
                            <th onclick="sortTable(3, 'table1')">High</th>
                            <th onclick="sortTable(4, 'table1')">Med</th>
                            <th onclick="sortTable(5, 'table1')">Change</th>
                            <th onclick="sortTable(6, 'table1')">%</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
           <script>
            document.addEventListener('DOMContentLoaded', () => {
	        const tableContainer = document.getElementById('posture_container');
		var table = document.getElementById("table1");
		var tbody = table.getElementsByTagName('tbody')[0];

		// Destroy the body if it has stuff
                tbody.innerHTML = '';

		// jwttokens should already be declared
                // Create one row for each token we manage
                jwtTokens.forEach(jwt => {
                var row = document.createElement('tr');
                var c0 = document.createElement('td');
		
		let link = "https://" + jwt.getAudience() + ".obsec.io";
                let displayText = jwt.getFriendlyName() !== "" ? jwt.getFriendlyName() : link;

                c0.innerHTML = `<a href="${link}" target="_blank">${displayText}</a>`;
                row.appendChild(c0);

		var c1 = document.createElement('td');
                c1.textContent = "NA";
		RunGQL(jwt.token, jwt.endpoint, q_listPostureScores)
                   .then(result => {
		       console.log(JSON.stringify(result));
                       c1.textContent = parseInt(result.data.listPostureScores.scores[0].score.value) + "%";
                })
                .catch(error => {
                console.error("An error occurred:", error);
                });
		row.appendChild(c1);



		var c2 = document.createElement('td');
		c2.textContent = "NA";
		row.appendChild(c2);

		var c3 = document.createElement('td');
		c3.textContent = "NA";
		row.appendChild(c3);

		var c4 = document.createElement('td');
		c4.textContent = "NA";
		row.appendChild(c4);
		
		RunGQL(jwt.token, jwt.endpoint, q_getPostureFailures)
                   .then(result => {
		       console.log(JSON.stringify(result));
		       c2.textContent = result.data.getPostureRuleAggregates.counts[0];
		       c3.textContent = result.data.getPostureRuleAggregates.counts[1];
		       c4.textContent = result.data.getPostureRuleAggregates.counts[2];
                })
                .catch(error => {
                console.error("An error occurred:", error);
                });

		var c5 = document.createElement('td');
		c5.textContent = "NA";
		row.appendChild(c5);

		var c6 = document.createElement('td');
		c6.textContent = "NA";
		row.appendChild(c6);

		tbody.appendChild(row);
               });
            });
	    </script>



<?php
} 

?>


