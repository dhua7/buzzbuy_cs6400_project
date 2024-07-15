<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

    $query = "SELECT first_name, last_name " .
		 "FROM User " .
		 "INNER JOIN RegularUser ON User.email = RegularUser.email " .
		 "WHERE User.email = '{$_SESSION['email']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User Profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
    
	
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$Comment = mysqli_real_escape_string($db, $_POST['Comment']);

	if (empty($Comment)) {
		 array_push($error_msg,  "Error: You must provide a Comment ");
	}
	else{
		$query = "INSERT INTO StatusUpdate (email, date_time, status_text) " .
					 "VALUES ('{$_SESSION['email']}', NOW(), '$Comment')";
					 
		$commentID = mysqli_query($db, $query);

        include('lib/show_queries.php');

        if (mysqli_affected_rows($db) == -1) {
             array_push($error_msg, "Error: Failed to add Comment: '" . $Comment .  "'<br>" . __FILE__ ." line:". __LINE__ );
        } 
            
	}
}

?>

<?php include("lib/header.php"); ?>
		<title>GTOnline Status Updates</title>
	</head>
	
	<body>
		<div id="main_container">
        <?php include("lib/menu.php"); ?>
			
        <div class="center_content">
            <div class="center_left">
                <div class="title_name">
                    <?php print $row['first_name'] . ' ' . $row['last_name']; ?>
                </div>          
                <div class="features">   
                        
                            <div class="profile_section">						
                                <div class="subtitle">Status Updates    (Note: intentionally missing functionality)</div>
                                <form name="searchform" action="view_updates.php" method="POST">
                                    <table >								
                                        <tr>
                                            <td class="item_label">Add Comment</td>
                                            <td><input type="textbox" name="Comment" /></td>
                                        </tr>
                                    </table>
                                    <a href="javascript:searchform.submit();" class="fancy_button">Update</a> 					
                                    </form>							
                            </div>
                        
            
                        <div class="profile_section">
                                <div class="subtitle">Prior Status Updates</div>
                                
                                <?php			
                                    $query = "SELECT date_time, status_text " .
                                             "FROM StatusUpdate " .
                                             "WHERE StatusUpdate.email = '{$_SESSION['email']}'";
                                    
                                    $result = mysqli_query($db, $query);
                                    
                                    include('lib/show_queries.php'); 
                                    if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                        array_push($error_msg,  "Query ERROR: Failed to get friend requests you sent <br>" . __FILE__ ." line:". __LINE__ );
                                    }
                                    
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                    $count = mysqli_num_rows($result);
                                   
                                   if ($row) {
                                                            
                                        print '<table>';
                                        print '<tr>';
                                        print '<td class="heading">Date</td>';
                                        print '<td class="heading">Status</td>';
                                        print '</tr>';
                                    
                                        while ($row){            
                                            print '<tr>';
                                            print '<td>' . $row['date_time'] . '</td>';
                                            print '<td>' . $row['status_text'] . '</td>';
                                            print '</tr>';
                                            
                                            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                        }
                                            print '</table>';
                                   }
                                    ?>
                              </div>     
                        </div> 			
                    </div> 

                    <?php include("lib/error.php"); ?>
                        
                    <div class="clear"></div> 		
            </div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>