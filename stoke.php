<?php
session_start();
include 'connection.php';

$message = "";
$mess = "";

// -------------------- HANDLE FORM SUBMISSION --------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Insert
    if (isset($_POST['insert'])) {
        if (!empty($_POST['name']) && !empty($_POST['price']) && !empty($_POST['quantity'])) {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];

            $sql = "INSERT INTO users (name, price, quantity) VALUES ('$name', '$price', '$quantity')";
            if (mysqli_query($connection, $sql)) {
                $message = "Product added successfully!";
            } else {
                $mess = "Error: " . mysqli_error($connection);
            }
        } else {
            $mess = "All fields are required!";
        }
    }

    // Update
    if (isset($_POST['update'])) {
        if (!empty($_POST['id']) && (!empty($_POST['name']) || !empty($_POST['price']) || !empty($_POST['quantity']))) {
            $id = $_POST['id'];
            $fields = [];

            if (!empty($_POST['name'])) $fields[] = "name='".$_POST['name']."'";
            if (!empty($_POST['price'])) $fields[] = "price='".$_POST['price']."'";
            if (!empty($_POST['quantity'])) $fields[] = "quantity='".$_POST['quantity']."'";

            $sql = "UPDATE users SET ".implode(",", $fields)." WHERE id='$id'";
            if (mysqli_query($connection, $sql)) {
                $message = "Product updated successfully!";
            } else {
                $mess = "Error: " . mysqli_error($connection);
            }
        } else {
            $mess = "ID required to update and at least one field.";
        }
    }

    // Delete
    if (isset($_POST['delete'])) {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
            $sql = "DELETE FROM users WHERE id='$id'";
            if (mysqli_query($connection, $sql)) {
                $message = "Product deleted successfully!";
            } else {
                $mess = "Error: " . mysqli_error($connection);
            }
        } else {
            $mess = "ID is required to delete a product.";
        }
    }

    // Get/View - handled below
}

// -------------------- FETCH PRODUCTS --------------------
$sql = "SELECT * FROM users";
$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Stock Management</h2>

    <?php if(!empty($mess)) echo "<p>$mess</p>"; ?>
    <?php if(!empty($message)) echo "<p>$message</p>"; ?>

    <form action="" method="POST">
        <input type="number" name="id" placeholder="Enter ID for Update/Delete"><br><br>
        <input type="text" name="name" placeholder="Enter product name"><br><br>
        <input type="text" name="price" placeholder="Enter cost price"><br><br>
        <input type="text" name="quantity" placeholder="Enter quantity"><br><br>
<p>Click on bottom button to manage your stock </p>
        <!-- Four buttons -->
        <input type="submit" name="insert" value="Insert" class="btn">
        <input type="submit" name="update" value="Update" class="btn"><br><br>
        <input type="submit" name="delete" value="Delete" class="btn">
        <input type="submit" name="get" value="Get/View" class="btn"><br><br>
    </form>

    <!-- Display products table -->
    <?php
    if (isset($_POST['get']) || $_SERVER["REQUEST_METHOD"]=="POST") {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['name']."</td>
                    <td>".$row['quantity']."</td>
                    <td>".$row['price']."</td>
                  </tr>";
        }
        echo "</table>";
    }
    ?>
    <br>
</div>
</body>
</html>
