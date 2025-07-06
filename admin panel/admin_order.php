<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

//Update orders
if(isset($_POST['update_order'])){
    $order_id = $_POST['order_id'];
    $order_id = filter_var($order_id , FILTER_SANITIZE_STRING);
    
    $update_payment = $_POST['update_payment'];
    $update_payment = filter_var($update_payment , FILTER_SANITIZE_STRING);

    $update_pay = $con->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_pay->execute(array($update_payment , $order_id));
    $success_msg[] = 'Order Payment Status Updated';
}

//Delete Order
if(isset($_POST['delete_order'])){
    $delete_id = $_POST['order_id'];
    $delete_id = filter_var($delete_id , FILTER_SANITIZE_STRING);

    $verify_delete = $con->prepare("SELECT * FROM orders WHERE id = ?");
    $verify_delete->execute(array($delete_id));
    if($verify_delete->rowCount() > 0){
        $delete_order = $con->prepare("DELETE FROM orders WHERE id = ?");
        $delete_order->execute(array($delete_id));
        $success_msg[] = 'Order Deleted';
    }else{
        $warning_ms[] = 'Order Already Deleted';
    }
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


    <?php
    $status = isset($_GET['status']) ? $_GET['status'] : 'admin_order';

    if($status == 'canceled'){
    ?>

        <div class="main-container">
            <?php include "../components/admin_header.php"; ?>
            <section class="order-container">
                <div class="heading">
                    <h1>Total Canseled Orders</h1>
                    <img src="../image/separator-img.png">
                </div>
            
                <div class="box-container">
                    <?php
                    $select_order = $con->prepare("SELECT * FROM orders WHERE seller_id = ? AND status = ?");
                    $select_order->execute(array($seller_id , 'canceled'));
                    if($select_order->rowCount() > 0){
                        while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                    ?>

                    <div class="box">
                        <div class="status" style="color:red;">
                            <?php echo $fetch_order['status'] ?>
                        </div>
                        <div class="details">
                            <p>Name of product : <span class="product-name"><?php echo $fetch_order['product_name'] ?></span></p>
                            <p>User Name : <span><?php echo $fetch_order['name'] ?></span></p>
                            <p>User Id : <span><?php echo $fetch_order['user_id'] ?></span></p>
                            <p>Placed On : <span><?php echo $fetch_order['dates'] ?></span></p>
                            <p>User Number : <span><?php echo $fetch_order['number'] ?></span></p>
                            <p>User Email : <span><?php echo $fetch_order['email'] ?></span></p>
                            <p>Total amount : <span><?php echo $fetch_order['qty'] ?></span></p>
                            <p>Product Price : $<span><?php echo $fetch_order['price'] ?></span></p>
                            <p>Total Price : $<span><?php echo $fetch_order['price'] ?></span></p>
                            <p>Payment Method : <span><?php echo $fetch_order['method'] ?></span></p>
                            <p>User Address : <span><?php echo $fetch_order['address'] ?></span></p>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_order['id']; ?>">
                            <select name="update_payment" class="box" style="width: 90%;">
                                <option disabled selected><?php echo $fetch_order["status"]; ?></option>
                                <option value="in progress">in progress</option>
                                <option value="delivered">delivered</option>
                                <option value="canceled">canceled</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" name="update_order" value="Update Payment" class="btn">
                                <input type="submit" name="delete_order" value="Delete Order" class="btn" onclick="return confirm('Deleted This Order');">
                            </div>
                        </form>
                    </div>

                    <?php
                        }
                    }else{
                        echo '
                        <div class="empty">
                            <p>No Order Placed Yet!</p>
                        </div>
                        ';
                    }
                    ?>
                </div>
            </section>

        </div>

    <?php }elseif($status == 'delivered'){ ?>

        <div class="main-container">
            <?php include "../components/admin_header.php"; ?>
            <section class="order-container">
                <div class="heading">
                    <h1>Total Confirm Orders</h1>
                    <img src="../image/separator-img.png">
                </div>
            
                <div class="box-container">
                    <?php
                    $select_order = $con->prepare("SELECT * FROM orders WHERE seller_id = ? AND status = ?");
                    $select_order->execute(array($seller_id , 'delivered'));
                    if($select_order->rowCount() > 0){
                        while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                    ?>

                    <div class="box">
                        <div class="status" style="color:limegreen">
                            <?php echo $fetch_order['status'] ?>
                        </div>
                        <div class="details">
                            <p>Name of product : <span class="product-name"><?php echo $fetch_order['product_name'] ?></span></p>
                            <p>User Name : <span><?php echo $fetch_order['name'] ?></span></p>
                            <p>User Id : <span><?php echo $fetch_order['user_id'] ?></span></p>
                            <p>Placed On : <span><?php echo $fetch_order['dates'] ?></span></p>
                            <p>User Number : <span><?php echo $fetch_order['number'] ?></span></p>
                            <p>User Email : <span><?php echo $fetch_order['email'] ?></span></p>
                            <p>Total amount : <span><?php echo $fetch_order['qty'] ?></span></p>
                            <p>Product Price : $<span><?php echo $fetch_order['price'] ?></span></p>
                            <p>Total Price : $<span><?php echo $fetch_order['price'] ?></span></p>
                            <p>Payment Method : <span><?php echo $fetch_order['method'] ?></span></p>
                            <p>User Address : <span><?php echo $fetch_order['address'] ?></span></p>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_order['id']; ?>">
                            <select name="update_payment" class="box" style="width: 90%;">
                                <option disabled selected><?php echo $fetch_order["status"]; ?></option>
                                <option value="in progress">in progress</option>
                                <option value="delivered">delivered</option>
                                <option value="canceled">canceled</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" name="update_order" value="Update Payment" class="btn">
                                <input type="submit" name="delete_order" value="Delete Order" class="btn" onclick="return confirm('Deleted This Order');">
                            </div>
                        </form>
                    </div>

                    <?php
                        }
                    }else{
                        echo '
                        <div class="empty">
                            <p>No Order Placed Yet!</p>
                        </div>
                        ';
                    }
                    ?>
                </div>
            </section>

        </div>

    <?php }elseif($status == 'all'){ ?>

    
        <div class="main-container">
            <?php include "../components/admin_header.php"; ?>
            <section class="order-container">
                <div class="heading">
                    <h1>Total Orders Placed</h1>
                    <img src="../image/separator-img.png">
                </div>
            
                <div class="box-container">
                    <?php
                    $select_order = $con->prepare("SELECT * FROM orders WHERE seller_id = ?");
                    $select_order->execute(array($seller_id));
                    if($select_order->rowCount() > 0){
                        while($fetch_order = $select_order->fetch(PDO::FETCH_ASSOC)){
                    ?>

                    <div class="box">
                        <div class="status" style="color: <?php if($fetch_order['status'] == 'in progress'){ echo "orange";}elseif($fetch_order['status'] == 'delivered'){ echo "limegreen";}else{ echo 'red'; } ?>">
                            <?php echo $fetch_order['status'] ?>
                        </div>
                        <div class="details">
                            <p>Name of product : <span class="product-name"><?php echo $fetch_order['product_name'] ?></span></p>
                            <p>User Name : <span><?php echo $fetch_order['name'] ?></span></p>
                            <p>User Id : <span><?php echo $fetch_order['user_id'] ?></span></p>
                            <p>Placed On : <span><?php echo $fetch_order['dates'] ?></span></p>
                            <p>User Number : <span><?php echo $fetch_order['number'] ?></span></p>
                            <p>User Email : <span><?php echo $fetch_order['email'] ?></span></p>
                            <p>Total amount : <span><?php echo $fetch_order['qty'] ?></span></p>
                            <p>Product Price : $<span><?php echo $fetch_order['price'] ?></span></p>
                            <p>Total Price : $<span><?php echo ($fetch_order['price']*$fetch_order['qty']) ?></span></p>
                            <p>Payment Method : <span><?php echo $fetch_order['method'] ?></span></p>
                            <p>User Address : <span><?php echo $fetch_order['address'] ?></span></p>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $fetch_order['id']; ?>">
                            <select name="update_payment" class="box" style="width: 90%;">
                                <option disabled selected><?php echo $fetch_order["status"]; ?></option>
                                <option value="in progress">in progress</option>
                                <option value="delivered">delivered</option>
                                <option value="canceled">canceled</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" name="update_order" value="Update Payment" class="btn">
                                <input type="submit" name="delete_order" value="Delete Order" class="btn" onclick="return confirm('Deleted This Order');">
                            </div>
                        </form>
                    </div>

                    <?php
                        }
                    }else{
                        echo '
                        <div class="empty">
                            <p>No Order Placed Yet!</p>
                        </div>
                        ';
                    }
                    ?>
                </div>
            </section>

        </div>
    <?php }else {
        header("Location: dashboard.php");
        exit();
        } 
    ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>