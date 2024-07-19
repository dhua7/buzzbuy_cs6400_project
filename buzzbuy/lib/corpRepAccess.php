<?php 
// SQL query to check access
$corpQuery = "SELECT User.EmployeeID, COUNT(CanAccess.DistrictNumber) as CountDistrictAccess 
        FROM User 
        JOIN CanAccess ON User.EmployeeID = CanAccess.EmployeeID 
        GROUP BY User.EmployeeID 
        HAVING COUNT(CanAccess.DistrictNumber) = (SELECT COUNT(DistrictNumber) FROM District)";

// Execute query
$corpResult = mysqli_query($db, $corpQuery);

// Check if user has access
$hasAccess = false;
if ($corpResult->num_rows > 0) {
    while($row = $corpResult->fetch_assoc()) {
        if ($row['EmployeeID'] == $_SESSION['employeeid']) {
            $hasAccess = true;
            break;
        }
    }
}

if (!$hasAccess) {
    echo "<script>
            alert('Access Denied');
            window.location.href = '" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'view_main.php') . "';
          </script>";
    exit();
}

?>