<?php

if( !isset($_GET['name']) ){
    $nonameError = 'No name given, please go back to <a href="orders.php">order list</a>.';
}else{
    $customerName = $_GET['name'];

    $data = getOrderDetails($customerName);
}

function getOrderDetails($id)
{

    if( intval($id) == 0 ) return 'Customer ID is not valid';

    // create connection to database
    $dbConnection = new mysqli("SCRUBBED FOR SAFETY","SCRUBBED FOR SAFETY","SCRUBBED FOR SAFETY","SCRUBBED FOR SAFETY");

    // Check connection
    if ($dbConnection -> connect_errno) {
        return "Failed to connect to MySQL: " . $dbConnection->connect_error;
    }

    // raw query
    $sql = "SELECT * FROM customers where customerNumber = ?";

    // prepare query and generate statement to execute.
    $stmt = $dbConnection->prepare($sql);
    // bind ? to data
    $stmt->bind_param('i', $id );

    // execute query
    $stmt->execute();

    // get result set
    $results = $stmt->get_result();

    // get ALL results
    $customerData = $results->fetch_array(MYSQLI_ASSOC);

    // close connection
    $dbConnection->close();

    return $customerData;

}


?>

<html lang="en">
<head>
    <title>Details</title>
</head>

<body>

<?php if( isset($nonameError) ): ?>
    <div style="background-color: lightcoral; border: red; padding: 1em; margin: 1em; border-radius: 10px;">
        <p style="color: red"><?= $nonameError ?></p>
    </div>
<?php endif ?>

<!--<pre>--><?php //var_dump($data) ?><!--</pre>-->

<?php if( isset($data)): ?>

    <?php if( is_array($data)): ?>
        <table style="width: 100%">
            <tr>
                <th>Customer Number:</th>
                <td><?= $data['customerNumber'] ?></td>
            </tr>
            <tr>
                <th>Customer Name:</th>
                <td><?= $data['customerName'] ?></td>
            </tr>
            <tr>
                <th>Contact:</th>
                <td><?= $data['contactFirstName'] ?> <?= $data['contactLastName'] ?></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td><?= $data['phone'] ?></td>
            </tr>
            <tr>
                <th>Address:</th>
                <td>
                    <p><?= $data['addressLine1'] ?></p>
                    <p><?= $data['addressLine2'] ?></p>
                    <p><?= $data['city'] ?>, <?= $data['state'] ?> <?= $data['postalCode'] ?></p>
                    <p><?= $data['country'] ?></p>
                </td>
            </tr>
            <tr>
                <th>Credit Limit:</th>
                <td><?= $data['creditLimit'] ?></td>
            </tr>
        </table>
    <?php else: ?>
        <div style="background-color: lightcoral; border: red; padding: 1em; margin: 1em; border-radius: 10px;">
            <p style="color: red"><?= $data ?></p>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div style="background-color: lightblue; border: blue; padding: 1em; margin: 1em; border-radius: 10px;">
        <p style="color: cornflowerblue">No customer records found by that ID</p>
    </div>
<?php endif; ?>

<a href="orders.php">Back to Order List</a>

</body>
</html>