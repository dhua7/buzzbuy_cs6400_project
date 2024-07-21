<?php
include('lib/common.php');
// written by Team 34

if (!isset($_SESSION['employeeid'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['manufacturer'])) {
    header('Location: view_manufacturer.php');
    exit();
}

$manufacturerName = urldecode($_GET['manufacturer']);

// Fetch manufacturer summary information
$query = "SELECT Manufacturer.ManufacturerName, COUNT(Product.PID) AS ProductCount, ROUND(AVG(Product.RetailPrice),2) AS AveragePrice, MAX(Product.RetailPrice) AS MaxPrice, MIN(Product.RetailPrice) AS MinPrice
          FROM Manufacturer JOIN Product ON Product.ManufacturerName = Manufacturer.ManufacturerName
          WHERE Manufacturer.ManufacturerName = '$manufacturerName'
          GROUP BY Manufacturer.ManufacturerName";

$summaryResult = mysqli_query($db, $query);
if ($summaryResult) {
    $summaryRow = mysqli_fetch_array($summaryResult, MYSQLI_ASSOC);
}

// Fetch manufacturer product details
$query = "SELECT Product.ProductName, Product.PID, Product.RetailPrice, Assignto.CategoryName
          FROM Product, Manufacturer, Assignto
          WHERE Product.ManufacturerName = '$manufacturerName' AND Product.ManufacturerName = Manufacturer.ManufacturerName AND Product.PID = Assignto.PID
		  ORDER BY Product.RetailPrice DESC";

$detailResult = mysqli_query($db, $query);
?>

<?php include("lib/header.php"); ?>
    <title>BuzzBuy Manufacturer Details</title>
</head>

<body>
    <div id="main_container">
        <?php include("lib/menu.php"); ?>
        
        <div class="center_content">
            <div class="center_left">
                <div class="title_name"><?php print $user_name; ?></div>          
                
                <div class="features">       
                    <div class="profile_section">
                        <div class="subtitle">Manufacturer Details</div>
                        <div class="subtitle">Manufacturer: <?php echo $summaryRow['ManufacturerName']; ?></div>
                        <table>
                            <tr>
                                <td class="heading">Total Number of Products</td>
                                <td class="heading">Average Retail Price</td>
                                <td class="heading">Minimum Retail Price</td>
                                <td class="heading">Maximum Retail Price</td>
                            </tr>
                            <tr>
                                <td><?php echo $summaryRow['ProductCount']; ?></td>
                                <td><?php echo $summaryRow['AveragePrice']; ?></td>
                                <td><?php echo $summaryRow['MinPrice']; ?></td>
                                <td><?php echo $summaryRow['MaxPrice']; ?></td>
                            </tr>
                        </table>

                        <div class="subtitle">Products</div>
                        <table>
                            <tr>
                                <td class="heading">Product Name</td>
                                <td class="heading">PID</td>
                                <td class="heading">Retail Prices</td>
                                <td class="heading">Category Name</td>
                            </tr>
                            <?php
                            while ($row = mysqli_fetch_array($detailResult, MYSQLI_ASSOC)) {
                                print "<tr>";
                                print "<td>{$row['ProductName']}</td>";
                                print "<td>{$row['PID']}</td>";
                                print "<td>{$row['RetailPrice']}</td>";
                                print "<td>{$row['CategoryName']}</td>";
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