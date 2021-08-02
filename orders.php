<?php
session_start();

// create connection to database
$dbConnection = new mysqli("SCRUBBED FOR SAFETY","SCRUBBED FOR SAFETY","SCRUBBED FOR SAFETY","SCRUBBED FOR SAFETY");

// Check connection
if ($dbConnection -> connect_errno) {
    echo "Failed to connect to MySQL: " . $dbConnection->connect_error;
    exit();
}

//default sort
$sortColumnName = 'orderNumber';


// check if a desired sort was clicked
if( isset($_GET['sort']) ){
    $desiredSort = $_GET['sort'];

    switch ($desiredSort){
        case ($desiredSort == 'ordernumber'):
            $sortColumnName = 'orderNumber';
            break;
        case ($desiredSort == 'date'):
            $sortColumnName = 'orderDate';
            break;
        case ($desiredSort == 'shipdate'):
            $sortColumnName = 'shippedDate';
            break;
        case ($desiredSort == 'status'):
            $sortColumnName = 'status';
            break;
        case ($desiredSort == 'custname'):
            $sortColumnName = 'customerName';
            break;
    }
}

//query
$sql = "SELECT orders.orderNumber, orders.orderDate, orders.shippedDate, orders.status, orders.customerNumber, customers.customerName FROM orders INNER JOIN customers ON orders.customerNumber = customers.customerNumber ORDER BY $sortColumnName";

//gen statement
$stmt = $dbConnection ->prepare($sql);

//execute statement
$stmt->execute();

// get result set
$results = $stmt->get_result();

// get ALL results
$orders = $results->fetch_all(MYSQLI_ASSOC);

// close connection
$dbConnection->close();

?>

<html lang="en">
<head>
    <!--
    Cameron Woodruff,
    WEB2603-WW1,
    Spring 2021,
    Project 11,
    -->
    <meta charset="UTF-8">
    <title>WEB2603 - Cameron Woodruff</title>
</head>
<body>

<table style="width: 100%;">
    <thead>
    <tr>
        <th>Order Number <a href="orders.php?sort=ordernumber">sort</a></th>
        <th>Order Date <a href="orders.php?sort=date">sort</a></th>
        <th>Ship Date <a href="orders.php?sort=shipdate">sort</a></th>
        <th>Status <a href="orders.php?sort=status">sort</a></th>
        <th>Customer Name <a href="orders.php?sort=custnname">sort</a></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order):

    $date = strtotime($order['orderDate']);
    $shipdate = "";
    $error = "";

    if(empty($order['shippedDate']) == true){
        $shipdate = "Not Shipped";
        }else{
        $shipdate = $order['shippedDate'];
        }

    if($order['status'] == "Cancelled"){
        $error = "background-color:red;";
    }
    ?>

        <tr style="<?= $error ?>">
            <td><?= $order['orderNumber'] ?></a></td>
            <td><?= date("d/m/Y", $date) ?></td>
            <td><?= $shipdate ?></td>
            <td><?= $order['status'] ?></td>
            <td><a href="detail.php?name=<?= $order['customerNumber'] ?>"><?= $order['customerName'] ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
</table>

</body>
</html>
