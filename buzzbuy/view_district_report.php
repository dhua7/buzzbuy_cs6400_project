<?php
include('lib/common.php');

session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['employeeid'])) {
    header('Location: login.php');
    exit();
}

// Fetch user information
$query = "SELECT firstname, lastname FROM User WHERE User.employeeid='{$_SESSION['employeeid']}'";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if (!is_bool($result) && (mysqli_num_rows($result) > 0)) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $user_name = $row['firstname'] . ' ' . $row['lastname'];
} else {
    array_push($error_msg, "Query ERROR: Failed to get User information...<br>" . __FILE__ ." line:". __LINE__);
}

// Fetch years for the dropdown
$years_query = "SELECT DISTINCT YEAR(DateSold) AS Year FROM Sells ORDER BY Year";
$years_result = mysqli_query($db, $years_query);
if (!$years_result) {
    die("Years Query Failed: " . mysqli_error($db));
}

// Fetch months for the dropdown
$months_query = "SELECT DISTINCT MONTH(DateSold) AS Month FROM Sells ORDER BY Month";
$months_result = mysqli_query($db, $months_query);
if (!$months_result) {
    die("Months Query Failed: " . mysqli_error($db));
}
?>

<?php include("lib/header.php"); ?>
<title>GTOnline View District with Highest Volume</title>
</head>

<body>
    <div id="main_container">
        <?php include("lib/menu.php"); ?>

        <div class="center_content">
            <div class="center_left">
                <div class="title_name"><?php print $user_name; ?></div>

                <div class="features">
                    <div class="profile_section">
                        <div class="subtitle">District with Highest Volume for Each Category</div>

                        <form method="GET" action="view_district_report.php">
                            <label for="year">Select Year:</label>
                            <select name="year" id="year">
                                <?php
                                while ($year_row = mysqli_fetch_array($years_result, MYSQLI_ASSOC)) {
                                    print "<option value='{$year_row['Year']}'>{$year_row['Year']}</option>";
                                }
                                ?>
                            </select>

                            <label for="month">Select Month:</label>
                            <select name="month" id="month">
                                <?php
                                while ($month_row = mysqli_fetch_array($months_result, MYSQLI_ASSOC)) {
                                    $monthName = date("F", mktime(0, 0, 0, $month_row['Month'], 10));
                                    print "<option value='{$month_row['Month']}'>{$monthName}</option>";
                                }
                                ?>
                            </select>

                            <input type="submit" value="View Report">
                        </form>

                        <?php
                        if (isset($_GET['year']) && isset($_GET['month'])) {
                            $selected_year = $_GET['year'];
                            $selected_month = $_GET['month'];

                            $query = "
                            WITH CategoryDistrictSales AS (
                                SELECT Assignto.CategoryName, District.DistrictNumber, 
                                    MAX(CASE WHEN YEAR(Sells.DateSold) = '$selected_year' 
                                        AND MONTH(Sells.DateSold) = '$selected_month' 
                                        THEN Sells.QuantitySold ELSE 0 END) AS MaxUnitsSold
                                FROM Sells 
                                JOIN Store ON Sells.StoreNumber = Store.StoreNumber 
                                JOIN District ON Store.DistrictNumber = District.DistrictNumber 
                                JOIN Product ON Sells.PID = Product.PID 
                                JOIN Assignto ON Product.PID = Assignto.PID  
                                GROUP BY Assignto.CategoryName, District.DistrictNumber), 
                            MaxCategorySales AS (
                                SELECT CategoryName, MAX(MaxUnitsSold) AS MaxUnitsSold  
                                FROM CategoryDistrictSales  
                                GROUP BY CategoryName)
                            SELECT CategoryDistrictSales.CategoryName, CategoryDistrictSales.DistrictNumber, 
                                CategoryDistrictSales.MaxUnitsSold AS MaxUnitsSold  
                            FROM CategoryDistrictSales 
                            JOIN MaxCategorySales 
                            ON CategoryDistrictSales.CategoryName = MaxCategorySales.CategoryName 
                                AND CategoryDistrictSales.MaxUnitsSold = MaxCategorySales.MaxUnitsSold  
                            ORDER BY CategoryDistrictSales.CategoryName ASC";

                            $result = mysqli_query($db, $query);
                            if (!empty($result) && (mysqli_num_rows($result) == 0)) {
                                array_push($error_msg, "SELECT ERROR: find District with Highest Volume <br>" . __FILE__ ." line:". __LINE__);
                            }
                        ?>

                        <table>
                            <tr>
                                <td class="heading">Category Name</td>
                                <td class="heading">District Number</td>
                                <td class="heading">Max Units Sold</td>
                            </tr>

                            <?php
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                print "<tr>";
                                print "<td>{$row['CategoryName']}</td>";
                                print "<td>{$row['DistrictNumber']}</td>";
                                print "<td>{$row['MaxUnitsSold']}</td>";
                                print "</tr>";
                            }
                            ?>
                        </table>
                        <?php } ?>
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
