<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

    // ERROR: demonstrating SQL error handlng, to fix
    // replace 'sex' column with 'gender' below:
    $query = "SELECT first_name, last_name, gender, birthdate, current_city, home_town " .
		 "FROM User INNER JOIN RegularUser ON User.email=RegularUser.email " .
		 "WHERE User.email='{$_SESSION['email']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
?>

<?php include("lib/header.php"); ?>
<title>GTOnline Profile</title>
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
                    <div class="subtitle">View Profile</div>   
                    <table>
                        <tr>
                            <td class="item_label">Sex</td>
                            <td>
                                <?php if ($row['gender'] == 'Male') { print 'Male';} else {print 'Female';} ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Birthdate</td>
                            <td>
                                <?php print $row['birthdate'];?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_label">Current City</td>
                            <td>
                                <?php print $row['current_city'];?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Hometown</td>
                            <td>
                                <?php print $row['home_town'];?>
                            </td>
                        </tr>

                        <tr>
                            <td class="item_label">Interests</td>
                            <td>
                                <ul>
                                    <?php
                                            $query = "SELECT interest FROM UserInterest WHERE email='{$_SESSION['email']}'";
                                            $result = mysqli_query($db, $query);
                                            
                                            include('lib/show_queries.php');
                                             
                                             if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get User interests...<br>" . __FILE__ ." line:". __LINE__ );
                                             }
                                                 
                                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                                print "<li>{$row['interest']}</li>";
                                            }
										?>
                                </ul>
                            </td>
                        </tr>
                    </table>						
                </div>

                <div class="profile_section">
                    <div class="subtitle">Education</div>  
                    <table>
                        <tr>
                            <td class="heading">School</td>
                            <td class="heading">Year Graduated</td>
                        </tr>							

                        <?php
									    $query = "SELECT school_name, year_graduated " . 
											 "FROM Attend " .
											 "WHERE email='{$_SESSION['email']}' " .
											 "ORDER BY year_graduated DESC";
									    $result = mysqli_query($db, $query);
                                        include('lib/show_queries.php');
                                        
                                        if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get School information...<br>" . __FILE__ ." line:". __LINE__ );
                                             } 
                                             
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
										print "<tr>";
										print "<td>" . $row['school_name'] . "</td>";
										print "<td>" . $row['year_graduated'] . "</td>";
										print "</tr>";
									}
								?>
                    </table>						
                </div>	

                <div class="profile_section">
                    <div class="subtitle">Professional</div>  
                    <table>
                        <tr>
                            <td class="heading">Employer</td>
                            <td class="heading">Job Title</td>
                        </tr>							

                        <?php
                                        $query = "SELECT employer_name, job_title " . 
											 "FROM Employment " .
											"WHERE email='{$_SESSION['email']}' " .
											 "ORDER BY employer_name DESC";
									   $result = mysqli_query($db, $query);
                                       
                                       include('lib/show_queries.php');
                                       
                                       if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                             array_push($error_msg,  "Query ERROR: Failed to get Employment information..." . __FILE__ ." line:". __LINE__ );
                                        } 
                                             
									while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
										print "<tr>";
										print "<td>" . $row['employer_name'] . "</td>";
										print "<td>" . $row['job_title'] . "</td>";
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
	</body>
</html>