<?php
include('lib/common.php');
// written by Team 34

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$enteredEmployeeid = mysqli_real_escape_string($db, $_POST['employeeid']);
	$enteredPassword = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($enteredEmployeeid)) {
            array_push($error_msg,  "Please enter an employee id.");
    }

	if (empty($enteredPassword)) {
			array_push($error_msg,  "Please enter a password.");
	}
	
    if ( !empty($enteredEmployeeid) && !empty($enteredPassword) )   { 

        $query = "SELECT employeeid FROM User WHERE employeeid='$enteredEmployeeid' AND '$enteredPassword' = (SELECT lastfourssn + '-' + lastname FROM User WHERE employeeid = '$enteredEmployeeid')";
        
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result); 
        
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            //$storedPassword = $row['password']; 
			$storedEmployeeid = $row['employeeid']; 
            
            $options = [
                'cost' => 8,
            ];
			
			$_SESSION['employeeid'] = $storedEmployeeid;
            array_push($query_msg, "logging in... ");
            header(REFRESH_TIME . 'url=view_main.php');		//to view the password hashes and login success/failure
            
        } else {
                array_push($error_msg, "The username entered does not exist: " . $enteredEmployeeid);
            }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>BuzzBuy Login</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/buzzbuy_online_logo.png" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="BuzzBuy Online Logo"/>
            </div>
        </div>

        <div class="center_content">
            <div class="text_box">

                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">BuzzBuy Login</div>
                    <div class="login_form_row">
                        <label class="login_label">Employee ID:</label>
                        <input type="text" name="employeeid" value="0001" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Password:</label>
                        <input type="password" name="password" value="1234-Douglas" class="login_input"/>
                    </div>
                    <input type="image" src="img/login.gif" class="login"/>
                    <form/>
                </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
            </div>
   
            <!-- 
			<div class="map">
			<iframe style="position:relative;z-index:999;" width="820" height="600" src="https://maps.google.com/maps?q=801 Atlantic Drive, Atlanta - 30332&t=&z=14&ie=UTF8&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a class="google-map-code" href="http://www.embedgooglemap.net" id="get-map-data">801 Atlantic Drive, Atlanta - 30332</a><style>#gmap_canvas img{max-width:none!important;background:none!important}</style></iframe>
			</div>
             -->
					<?php include("lib/footer.php"); ?>

        </div>
    </body>
</html>