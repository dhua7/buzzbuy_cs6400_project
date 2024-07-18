<?php 
// SQL query to check access
$corpQuery = "SELECT User.EmployeeID, COUNT(CanAccess.DistrictNumber) as CountDistrictAccess 
        FROM User 
        JOIN CanAccess ON User.EmployeeID = CanAccess.EmployeeID 
        GROUP BY User.EmployeeID 
        HAVING COUNT(CanAccess.DistrictNumber) = (SELECT COUNT(DistrictNumber) FROM District)";

// Execute query
$corpResult = $conn->query($corpQuery);

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

$conn->close();

// If access is denied, handle it here
//if (!$hasAccess) {
//    echo "<script>alert('Access Denied');</script>";
//    header("Location: view_main.php"); // Redirect to a main or previous page
//    exit();
//}

//if (!$hasAccess) {
//    echo "<script>alert('Access Denied');</script>";
//    $referringPage = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'view_main.php';
//    header("Location: $referringPage");
//    exit();
//}


?>