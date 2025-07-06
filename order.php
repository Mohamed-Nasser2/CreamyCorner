<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
    header('Location:login.php');
}


if(isset($_POST['delete_item'])){
    $order_id = $_POST['order_id'];
    $order_id = filter_var($order_id , FILTER_SANITIZE_STRING);

    $verify_delete = $con->prepare("SELECT * FROM orders WHERE id = ?");
    $verify_delete->execute(array($order_id));

    if($verify_delete->rowCount() > 0){
        $delete_order_id = $con->prepare("DELETE FROM orders WHERE id = ?");
        $delete_order_id->execute(array($order_id));
        $success_msg[] = 'item removed from orders';
    }else{
        $warning_msg[] = 'item already removed';
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - user order Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>My orders</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>My orders </span>
        </div>
    </div>

    <div class="orders">
        <div class="heading">
            <h1>my orders</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <?php
            $select_orders = $con->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY dates DESC");
            $select_orders->execute(array($user_id));

            if($select_orders->rowCount() > 0){
                while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                    $product_id = $fetch_orders['product_id'];

                    $select_products = $con->prepare("SELECT * FROM products WHERE id = ?");
                    $select_products->execute(array($product_id));

                    if($select_products->rowCount() > 0){
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
            ?>

            <div class="box" <?php if($fetch_orders['status'] == 'canceled'){ echo 'style = "border:2px solid red"'; }elseif($fetch_orders['status'] == 'delivered'){ echo 'style = "border:2px solid limegreen"'; }else{ echo 'style = "border:2px solid orange"'; } ?>>
                <a href="view_order.php?get_id=<?php echo $fetch_orders['id'] ?>">
                    <img src="uploaded_files/<?php echo $fetch_products['image'] ?>" class="image">
                    <p class="date"> <i class="bx bxs-calendar-alt"></i> <?php echo $fetch_orders['dates'] ?></p>
                    <div class="content">
                        <img src="image/shape-19.png" class="shap">
                        <div class="row">
                            <h3 class="name"><?php echo $fetch_products['name'] ?></h3>
                            <form action="" method="POST">
                                <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id'] ?>">
                                <p class="price">Price : $<?php echo $fetch_products['price'] ?>/-</p>
                                <button type="submit" name="delete_item" onclick="return confirm('remove from orders')" class="bx bx-x fa-edit box"></button>
                            </form>
                            <p class="status" style="color:<?php if($fetch_orders['status'] == 'delivered'){ echo "green"; }elseif($fetch_orders['status'] == 'canceled'){  echo "red"; }else{ echo "orange"; } ?>"><?php echo $fetch_orders['status'] ?></p>
                        </div>
                    </div>
                </a>
            </div>

            <?php
                        }
                    }
                }
            }else{
                echo '
                <div class="empty">
                    <p>no order take placed yet</p>
                </div>
                ';
            }
            ?>
        </div>
    </div>


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>