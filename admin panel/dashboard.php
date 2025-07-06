<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Admin Dashboard Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="dashboard">
            <div class="heading">
                <h1>Dashboard</h1>
                <img src="../image/separator-img.png">
            </div>
        
            <div class="box-container">
                <div class="box">
                    <h3>Welcome !</h3>
                    <p><?php echo $fetch_profile['name'] ?></p>
                    <a href="update.php" class="btn">Update Profile</a>
                </div>
                <div class="box">
                    <?php
                    $select_message = $con->prepare("SELECT * FROM message");
                    $select_message->execute();
                    $number_of_message = $select_message->rowCount();
                    ?>
                    <h3><?php echo $number_of_message?></h3>
                    <p>Unread Message</p>
                    <a href="admin_message.php" class="btn">See Message</a>
                </div>
                <div class="box">
                    <?php
                    $select_products = $con->prepare("SELECT * FROM products WHERE seller_id = ?");
                    $select_products->execute(array($seller_id));
                    $number_of_products = $select_products->rowCount();
                    ?>
                    <h3><?php echo $number_of_products; ?></h3>
                    <p>Products Added</p>
                    <a href="add_products.php" class="btn">Add Product</a>
                </div>

                <div class="box">
                    <?php
                    $select_active_products = $con->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
                    $select_active_products->execute(array($seller_id , 'active'));
                    $number_of_avtive_products = $select_active_products->rowCount();
                    ?>
                    <h3><?php echo $number_of_avtive_products; ?></h3>
                    <p>Total Active Products</p>
                    <a href="view_product.php?status=active" class="btn">Active Product</a>
                </div>

                <div class="box">
                    <?php
                    $select_deactive_products = $con->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
                    $select_deactive_products->execute(array($seller_id , 'deactive'));
                    $number_of_deavtive_products = $select_deactive_products->rowCount();
                    ?>
                    <h3><?php echo $number_of_deavtive_products; ?></h3>
                    <p>Total Deactive Products</p>
                    <a href="view_product.php?status=deactive" class="btn">Deactive Product</a>
                </div>

                <div class="box">
                    <?php
                    $select_users = $con->prepare("SELECT * FROM users");
                    $select_users->execute();
                    $number_of_users = $select_users->rowCount();
                    ?>
                    <h3><?php echo $number_of_users?></h3>
                    <p>Users Account</p>
                    <a href="user_accounts.php" class="btn">See Users</a>
                </div>

                <div class="box">
                    <?php
                    $select_sellers = $con->prepare("SELECT * FROM sellers");
                    $select_sellers->execute();
                    $number_of_sellers = $select_sellers->rowCount();
                    ?>
                    <h3><?php echo $number_of_sellers?></h3>
                    <p>Sellers Account</p>
                    <a href="sellers_accounts.php" class="btn">See Sellers</a>
                </div>

                <div class="box">
                    <?php
                    $select_orders = $con->prepare("SELECT * FROM orders WHERE seller_id = ?");
                    $select_orders->execute(array($seller_id));
                    $number_of_orders = $select_orders->rowCount();
                    ?>
                    <h3><?php echo $number_of_orders?></h3>
                    <p>Total Orders Placed</p>
                    <a href="admin_order.php?status=all" class="btn">Total Orders</a>
                </div>

                <div class="box">
                    <?php
                    $select_confirm_orders = $con->prepare("SELECT * FROM orders WHERE seller_id = ? AND status = ?");
                    $select_confirm_orders->execute(array($seller_id , 'delivered'));
                    $number_of_confirm_orders = $select_confirm_orders->rowCount();
                    ?>
                    <h3><?php echo $number_of_confirm_orders?></h3>
                    <p>Total Confirm Orders</p>
                    <a href="admin_order.php?status=delivered" class="btn">Confirm Orders</a>
                </div>

                <div class="box">
                    <?php
                    $select_canseled_orders = $con->prepare("SELECT * FROM orders WHERE seller_id = ? AND status = ?");
                    $select_canseled_orders->execute(array($seller_id , 'canceled'));
                    $number_of_canseled_orders = $select_canseled_orders->rowCount();
                    ?>
                    <h3><?php echo $number_of_canseled_orders?></h3>
                    <p>Total Canseled Orders</p>
                    <a href="admin_order.php?status=canceled" class="btn">Canceled Orders</a>
                </div>

            </div>
        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>