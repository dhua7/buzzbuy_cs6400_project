<?php

include('lib/common.php');

//----------------------------------------------------------------
// written by Team 34
//
// this is to get a currently signed-in user's employee id from a client side session 
// also, this displays the SQL command that was used to get a user's information too.

if (!isset($_SESSION['employeeid'])) {
    header('Location: login.php');
    exit();
}

// just to display a signed-in user's information 
$query = "SELECT firstname, lastname " .
         "FROM User " .
         "WHERE User.employeeid='{$_SESSION['employeeid']}'";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');
 
if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get User information...<br>" . __FILE__ ." line:". __LINE__ );
}

?>

<?php include("lib/header.php"); ?>
        <title>BuzzBuy View Holidays</title>
    </head>
    
    <body>
        <div id="main_container">
            <?php include("lib/menu.php"); ?>
            
            <div class="center_content">
                <div class="center_left">
                    <div class="title_name"> </div>          
                    
                    <div class="features">       
                        <div class="profile_section">
                            <div class="subtitle">View Holidays</div>   
                            <table>
                                <tr>
                                    <td class="heading">Holiday Name</td>
                                    <td class="heading">Business Date</td>
                                    <td class="heading">Created By (Employee ID)</td>
                                </tr>
                                                                
                                <?php                                
                                    $query = "SELECT H.holidayname, H.businessdate, C.employeeid 
                                             FROM holidays H 
                                             JOIN Created C ON H.businessdate = C.businessdate 
                                             ORDER BY H.businessdate DESC";
                                             
                                    $result = mysqli_query($db, $query);
                                    include('lib/show_queries.php');
                                    if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                                         array_push($error_msg,  "SELECT ERROR: find Holidays <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                        print "<td>{$row['holidayname']}</td>";
                                        print "<td>{$row['businessdate']}</td>";
                                        print "<td>{$row['employeeid']}</td>";
                                        print "</tr>";                           
                                    }                                   
                                ?>
                            </table>                       
                        </div>  
                     </div> 
                </div> 
                
                <?php include("lib/error.php"); ?>
                    
                <div class="clear"></div> 
            </div>    

               <?php include("lib/footer.php"); ?>
        </div>
        <!-- add a log entry -->
        <!-- JL: report_name must be one of names defined in our "report" table, otherwise a log entry won't be added to the table. --> 
        <?php 
            $report_name = "Holidays";
            $timestamp = date("Y-m-d H:i:s");
    
            // Escape variables for safety
            $escaped_employeeid = mysqli_real_escape_string($db, $_SESSION['employeeid']);
            $escaped_timestamp = mysqli_real_escape_string($db, $timestamp);
            $escaped_report_name = mysqli_real_escape_string($db, $report_name);
            
            $audit_query = "INSERT INTO auditlogentry (employeeid, reportname, timestamp) VALUES ('{$escaped_employeeid}', '{$escaped_report_name}', '{$escaped_timestamp}');";

            // Execute the query
            $result = mysqli_query($db, $audit_query);
            
            include('lib/show_queries.php');

            if ($result === False) {
                array_push($error_msg, "Error: Failed to add Audit Log Entry: " . mysqli_error($db));
            }
        ?>
    </body>
</html>