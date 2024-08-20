<?php
function LoadThreat($profile)
{
?>
            <div class="bubble">
                <h2>Threat Summary</h2>
                <table id="table2">
                    <thead>
                        <tr>
                            <th onclick="sortTable(0, 'table2')">Tenant Name</th>
                            <th onclick="sortTable(1, 'table2')">Total</th>
                            <th onclick="sortTable(2, 'table2')">Crit</th>
                            <th onclick="sortTable(3, 'table2')">High</th>
                            <th onclick="sortTable(4, 'table2')">Med</th>
                            <th onclick="sortTable(5, 'table2')">Change</th>
                            <th onclick="sortTable(6, 'table2')">%</th>
                            <th onclick="sortTable(7, 'table2')">Change</th>
                            <th onclick="sortTable(8, 'table2')">%</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
<?php
} 

?>


