<?php
// session start
session_start();
// Include database connection
include_once 'includes/dbConn.inc.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST['update'])) {
        // Retrieve the array of item IDs
        $itemIds = $_POST['itemIds'];

        // Assuming you have the cwId available in the POST data                
        $cwId = $_SESSION['cwId'];  
        
        // Loop through each item ID and update the quantity
        foreach ($itemIds as $itemId) {
            // Get the corresponding quantity for the current item ID
            $quantity = $_POST['qty'][$itemId];

            // Update the cart item in the database
            updateCartItem($conn, $cwId, $itemId, $quantity);

        }

        // redirect to the cart page
        header("Location: cart.php");
    }

    // Check if the "Remove Item" button was clicked
    elseif (isset($_POST['remove'])) {
        // Get the item ID from the query string
        $itemId = $_GET['itemId'];

        // Assuming you have the cwId available in the POST data                
        $cwId = $_SESSION['cwId'];  

        // Delete the cart item from the database
        deleteCartItem($conn, $cwId, $itemId);

        // redirect to the cart page
        header("Location: cart.php");
    }

    elseif (isset($_POST['add-qty'])) {
        // Get the item ID from the form data
        $itemId = $_POST['item-id'];

        // Assuming you have the cwId available in the POST data                
        $cwId = $_SESSION['cwId'];  

        // Get the quantity from the form data
        $quantity = $_POST['qty'];

        // Add the item to the cart in the database
        addQuantityToCartItem($conn, $cwId, $itemId, $quantity);

        // redirect to the cart page
        header("Location: cart.php");
    }

    
}

/**
 * Function to update the cart item quantity in the database
 *
 * @param mysqli $conn The database connection
 * @param int $itemId The ID of the item to update
 * @param int $quantity The new quantity for the item
 * @return void
 */
function updateCartItem($conn,$cwId, $itemId, $quantity) {
    // Prepare an update query
    $updateQuery = "UPDATE itemcartwishlist SET cartQuantity = ? WHERE itemId = ? AND cwId = ?";
    
    // Using prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($conn, $updateQuery);
    
    
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'iii', $quantity, $itemId, $cwId);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // echo "Item ID: $itemId updated successfully with quantity: $quantity<br>";
    } else {
        echo "Error updating item ID: $itemId - " . mysqli_stmt_error($stmt) . "<br>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

function deleteCartItem($conn,$cwId, $itemId) {
    // Prepare a delete query
    $deleteQuery = "DELETE FROM itemcartwishlist WHERE itemId = ? AND cwId = ?";
    
    // Using prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($conn, $deleteQuery);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, 'ii', $itemId, $cwId);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // echo "Item ID: $itemId deleted successfully<br>";
    } else {
        echo "Error deleting item ID: $itemId - " . mysqli_stmt_error($stmt) . "<br>";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

function addQuantityToCartItem($conn,$cwId, $itemId, $quantity) {

    // print all parameters
    echo 'cwId: '.$cwId.' itemId: '.$itemId.' quantity: '.$quantity;

    // check wherher the item is already in the cart or not
    $query = "SELECT * FROM itemcartwishlist WHERE cwId = $cwId AND itemId = $itemId";
    
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Item is already in the cart, update the quantity
        $row = mysqli_fetch_assoc($result);
        $newQuantity = $row['cartQuantity'] + $quantity;
        updateCartItem($conn, $cwId, $itemId, $newQuantity);


    } else {

        // echo 'Item is not in the cart';

        // Item is not in the cart, add it with the given quantity
        $insertQuery = "INSERT INTO itemcartwishlist (cwId, itemId, cartQuantity, cart) VALUES (?, ?, ?, 1)";
        
        // Using prepared statements to prevent SQL injection
        $stmt = mysqli_prepare($conn, $insertQuery);
        
        // Bind parameters
        mysqli_stmt_bind_param($stmt, 'iii', $cwId, $itemId, $quantity);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // echo "Item ID: $itemId added successfully with quantity: $quantity<br>";
        } else {
            echo "Error adding item ID: $itemId - " . mysqli_stmt_error($stmt) . "<br>";
        }
    
        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

// Close the database connection
mysqli_close($conn);
?>
