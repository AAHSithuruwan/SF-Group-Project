<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <link href="css/bootstrap-4.4.1.css" rel="stylesheet">
	<link href="css/purchase.css" rel="stylesheet" type="text/css">
    <link href="css/headerAndFooter.css" rel="stylesheet" type="text/css">
    <script src="js/purchase.js"></script>
	<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    </head>
    <body>
    <?php 
        if($_POST['total-amount']=='0'){
            header('Location:cart.php?error=Cart_Empty');
        }
    ?>
    <?php include_once 'header.php'; ?>
        <div class="payment-wrapper">
            <form class="pdetails" name="pdetails" action="includes/purchase.inc.php" method="post" onsubmit="return validatePurchase()">
                <table>
                    <tr>
                        <td><b>Total Amount</b></td>
                        <td><?php echo $_POST['total-amount']?></td>
                    </tr>
                   
                    <tr>
                        <td><b>Discount</b></td>
                        <td><?php echo $_POST['discount']?></td>
                    </tr>
                    <secssion calss="box">
                    <tr>
                        <td colspan="2"><center><h3>Card Details</h3></center></td>
                    </tr>

                    <tr>
                        <td>Card Number</td>
                        <td><input type="text" name="c-number" id="cNumber" placeholder="Enter Card Number "></td>

                    </tr>
                    <tr>
                        <td>Exp. Month</td>
                        <td> <input type="text" id="exp-month" name="exp-month" placeholder="Enter exp Month "></td>
                    </tr>
                    <tr>
                        <td>cvv</td>
                        <td><input type="text" id="cVV-number" name="cvv" placeholder="Enter Card cvv " ></td>
                    </tr>
                    <tr>
                    <td>Buyer Name</td>
                    <td><input type="text" id="buyer-Name" name="b-Name" placeholder="Enter Byer Name " ></td>
                    </tr>
                    <tr>
                        <td>Buyer Address</td>
                        <td><input type="text"id="bYER-aDDRESS" name="baDDRESS" placeholder="Enter Byer Address "></td>

                    </tr>
        
                    <tr>
                        <td></td>
                       <td> <center><input type="submit" class="btn btn-success" value="Pay" name="payBtn"></center></td>
                    </tr>
                </table>
            </form>
        </div>
    <?php include_once 'footer.php'; ?>
    <script src="js/jquery-3.4.1.min.js"></script>

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.4.1.js"></script>
    </body>
    </html>
