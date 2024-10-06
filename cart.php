<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - MobilePanet</title>
    <link rel="icon" href="assets/favicon-32x32.png" type="image/png">
    <link href="css/bootstrap-4.4.1.css" rel="stylesheet">
    <link href="css/headerAndFooter.css" rel="stylesheet" type="text/css">
    <link href="css/cart.css" rel="stylesheet" type="text/css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
    <?php include_once 'header.php'; ?>
    
    <div class="content-wrapper">
        <div class="container mt-5">
            <h1 class="my-4">Shopping Cart</h1>
        </div>

        <div class="container">
            <div class="cart">
                <!-- Cart items will be dynamically added here using JavaScript -->
                <form action="purchase.php" method="post">
                    <div class="carttable">
                        <?php

                        // Check whether the session is set or not
                        if (isset($_SESSION['log_name'])) {
                            include_once 'includes/dbConn.inc.php';
                            $cwIdSql = "SELECT cwId FROM cartwishlist WHERE userId = " . $_SESSION['log_id'];
                            $cwIdSqlResult = mysqli_query($conn, $cwIdSql);
                            $cwId = mysqli_fetch_assoc($cwIdSqlResult);
                            // set the session variable
                            $_SESSION['cwId'] = $cwId['cwId'];
                            $query = "SELECT itemId, cartQuantity FROM itemcartwishlist WHERE cwId = " . $cwId['cwId'] . " AND cart = 1";
                            $queryResult = mysqli_query($conn, $query);
                            $totalAmount = 0;

                            if(mysqli_num_rows($queryResult) > 0){
                                echo '
                                <table class="table table-hover">
                                    <thead>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                ';

                                while ($record = mysqli_fetch_assoc($queryResult)) {
                                    if ($record == 0) {
                                        echo '<tr><td colspan="5">Please login to view your cart</td></tr>';
                                        continue;
                                    }

                                    $itemDetSql = "SELECT itemName, sellingPrice FROM item WHERE itemId = " . $record['itemId'];
                                    $itemDetSqlResult = mysqli_query($conn, $itemDetSql);
                                    
                                    if ($itemDet = mysqli_fetch_assoc($itemDetSqlResult)) {
                                        echo '
                                        <input type="hidden" name="item-id" value="' . $record['itemId'] . '">
                                        <input type="hidden" name="cw-id" value="' . $cwId['cwId'] . '">
                                        <tr>
                                            <td>' . $itemDet["itemName"] . '</td>
                                            <td>' . $itemDet["sellingPrice"] . '</td>
                                            <td><input type="text" class="qty-input" name="qty[' . $record['itemId'] . ']" value="' . $record["cartQuantity"] . '"></td>
                                            <td>' . $itemDet["sellingPrice"] * $record["cartQuantity"] . '</td>
                                            <td>
                                                <button type="submit" name="remove" formaction="Cart-Update-Query.php?itemId='.$record['itemId'].'" class="close" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                                <input type="hidden" name="itemIds[]" value="' . $record['itemId'] . '">
                                            </td>
                                        </tr>
                                        ';
                                        $totalAmount += $itemDet["sellingPrice"] * $record["cartQuantity"];
                                    }
                                }
                                echo '
                                    <input type="hidden" name="total-amount" value="' . $totalAmount . '">
                                    <input type="hidden" name="discount" value="0">
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td id="total-amount" class="text">' . $totalAmount . '</td>
                                        </tr>
                                    </tfoot>
                                    </tbody>
                                </table>
                                <div class="container">
                                ';
                            }else{
                                echo '<div class="alert alert-warning" role="alert">Your cart is empty!</div>';
                            }
                        } else {
                            echo 'Please sign in to view your cart </div>'; 
                        }
                        ?>
                    
                    <button type="submit" name="update" formaction="index.php" class="btn btn-primary my-4">Continue Shopping</button>
                    <?php 
                        if (isset($_SESSION['log_name'])) {
                            if ($queryResult) {
                                // Get the number of fetched rows
                                $numRows = mysqli_num_rows($queryResult);
                                if ($numRows > 0) {
                                    //checkout button
                                    echo '<button type="submit" name="cont-Shopping" class="btn btn-success my-4">Check Out</button>';
                                    echo '<button type="submit" name="update" id="cart-update-btn" formaction="Cart-Update-Query.php" class="btn btn-outline-secondary my-4">Update</button>';
                                }
                            }
                            //checkout button
                        }else{
                            // login button
                            echo '<button type="submit" name="login" formaction="signin.php" class="btn btn-success my-4">Login</button>';
                        }
                        echo '</div>';
                    ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <?php include_once 'footer.php'; ?>
    
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.4.1.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

</body>
</html>
