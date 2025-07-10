<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

$select_products = $con->prepare("SELECT * FROM products WHERE seller_id = ?");
$select_products->execute(array($seller_id));
$total_product = $select_products->rowCount();

$select_orders = $con->prepare("SELECT * FROM orders WHERE seller_id = ?");
$select_orders->execute(array($seller_id));
$total_orders = $select_orders->rowCount();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - seller profile Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="seller-profile">
            <div class="heading">
                <h1>Profile Details</h1>
                <img src="../image/separator-img.png">
            </div>
            <div class="details">
                <div class="seller">
                    <img src="../uploaded_files/<?php echo $fetch_profile['image']; ?>">
                    <h3 class="name"><?php echo $fetch_profile['name']; ?></h3>
                    <span>Seller</span>
                    <a href="update.php" class="btn">Update Profile</a>
                </div>
                <div class="flex">
                    <div class="box">
                        <span><?php echo $total_product; ?></span>
                        <p>Total Products</p>
                        <a href="view_product.php?status=all" class="btn">View Products</a>
                    </div>
                    <div class="box">
                        <span><?php echo $total_orders; ?></span>
                        <p>Total Orders</p>
                        <a href="admin_order.php?status=all" class="btn">View Orders</a>
                    </div>
                </div>
            </div>
            
        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>