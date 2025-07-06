<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

if(isset($_GET['get_id'])){
    $get_id = $_GET['get_id'];
}else{
    $get_id = '';
    header('Location:order.php');
}

if(isset($_POST['cancle'])){
    $update_order = $con->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_order->execute(array('canceled' , $get_id));

    header('Location:order.php');
}

if(isset($_POST['delete'])){
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
    <title>Blue Sky Summer - order detail Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>order detail</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>order detail </span>
        </div>
    </div>

    <div class="order-detail">
        <div class="heading">
            <h1>my order detail</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <?php
            $grand_total = 0;
            $select_order = $con->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
            $select_order->execute(array($get_id));

            if($select_order->rowCount() > 0){
                while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                    $select_product = $con->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
                    $select_product->execute(array($fetch_order['product_id']));
                    if($select_product->rowCount() > 0){
                        while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                            $sub_total = ($fetch_order['price']* $fetch_order['qty']);
                            $grand_total += $sub_total;
            ?>

            <div class="box">
                <div class="col">
                    <p class="title"><i class="bx bxs-calendar-alt"></i><?php echo $fetch_order['dates'] ?></p>
                    <img src="uploaded_files/<?php echo $fetch_product['image'] ?>" class="image">
                    <p class="price">$<?php echo $fetch_product['price'] ?>/-</p>
                    <h3 class="name"><?php echo $fetch_product['name'] ?></h3>
                    <p class="grand-total">total amount payable : $<span><?php echo $grand_total ?></span></p>
                </div>
                <div class="col">
                    <p class="title">billing address</p>
                    <p class="user"><i class="bi bi-person-bounding-box"></i><?php echo $fetch_order['name'] ?></p>
                    <p class="user"><i class="bi bi-phone"></i><?php echo $fetch_order['number'] ?></p>
                    <p class="user"><i class="bi bi-envelope"></i><?php echo $fetch_order['email'] ?></p>
                    <p class="user"><i class="bi bi-pin-map-fill"></i><?php echo $fetch_order['address'] ?></p> 
                    <p class="status" style="color:<?php if($fetch_order['status'] == 'delivered'){ echo "green"; }elseif($fetch_order['status'] == 'canceled'){  echo "red"; }else{ echo "orange"; } ?>"><?php echo $fetch_order['status'] ?></p>
                    <?php if($fetch_order['status'] == 'canceled'){ ?>
                        <a href="checkout.php?get_id=<?php echo $fetch_product['id'] ?>" class="btn">order again</a>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_order['id'] ?>">
                            <button type="submit" style="width:35%" name="delete" class="btn" onclick="return confirm('do you want to delete this product')">delete</button>
                            </form>
                    <?php }else{ ?>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_order['id'] ?>">
                            <button type="submit" name="cancle" class="btn" onclick="return confirm('do you want to cancle this product')">cancel</button>
                            <button type="submit" name="delete" class="btn" onclick="return confirm('do you want to delete this product')">delete</button>
                        </form>
                    <?php } ?>
                </div>
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