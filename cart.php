<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = 'Location:login.php';
}


//update qty in cart

if(isset($_POST['update_cart'])){
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id , FILTER_SANITIZE_STRING);

    $qty = $_POST['qty'];
    $qty = filter_var($qty , FILTER_SANITIZE_STRING);

    $update_qty = $con->prepare("UPDATE cart SET qty = ? WHERE id = ?");
    $update_qty->execute(array($qty , $cart_id));

    $success_msg[] = 'cart quantity updated';
}

//delete product from cart

if(isset($_POST['delete_item'])){
    $cart_id = $_POST['cart_id'];
    $cart_id = filter_var($cart_id , FILTER_SANITIZE_STRING);

    $verify_delete_item = $con->prepare("SELECT * FROM cart WHERE id = ?");
    $verify_delete_item->execute(array($cart_id));

    if($verify_delete_item->rowCount() > 0){
        $delete_cart_id = $con->prepare("DELETE FROM cart WHERE id = ?");
        $delete_cart_id->execute(array($cart_id));

        $success_msg[] = 'cart item deleted';
    }else{
        $warning_msg[] = 'cart item already deleted';
    }
}

//empty cart

if(isset($_POST['empty_cart'])){
    $verify_empty_item = $con->prepare("SELECT * FROM cart WHERE user_id = ?");
    $verify_empty_item->execute(array($user_id));

    if($verify_empty_item->rowCount() > 0){
        $delete_cart_id = $con->prepare("DELETE FROM cart WHERE user_id = ?");
        $delete_cart_id->execute(array($user_id));

        $success_msg[] = 'empty cart successfully';
    }else{
        $warning_msg[] = 'your cart is already empty';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - user cart Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Cart</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Cart </span>
        </div>
    </div>

    <div class="products">
        <div class="heading">
            <h1>My Cart</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <?php
            $grand_total = 0;
            
            $select_cart = $con->prepare("SELECT * FROM cart WHERE user_id = ?");
            $select_cart->execute(array($user_id));

            if($select_cart->rowCount() > 0){
                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
                    $select_product = $con->prepare("SELECT * FROM products WHERE id = ?");
                    $select_product->execute(array($fetch_cart['product_id']));

                    if($select_product->rowCount() > 0){
                        $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
            ?>

            <form action="" method="POST" class="box <?php if($fetch_product['stock'] == 0){ echo "disabled";} ?>">
                <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id'] ?>">
                <img src="uploaded_files/<?php echo $fetch_product['image'] ?>" class="image">
                <?php if($fetch_product['stock'] > 9){ ?>
                    <span class="stock" style="color: green;">in stock</span>
                <?php }elseif($fetch_product['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">out of stock</span>
                <?php }else{ ?>
                    <span class="stock" style="color: red;">Hurry, only <?php echo $fetch_product['stock']; ?> left</span>
                <?php } ?>

                <div class="content">
                    <img src="image/shape-19.png" class="shap">
                    <h3 class="name"><?php echo $fetch_product['name']; ?></h3>
                    <div class="flex-btn">
                        <p class="price">price $<?php echo $fetch_product['price'] ?>/-</p>
                        <input type="number" name="qty" required min="1" value="<?php echo $fetch_cart['qty'] ?>" max="99" maxlength="2" class="box qty">
                        <button type="submit" name="update_cart" class="bx bxs-edit fa-edit box"></button>
                    </div>
                    <div class="flex-btn">
                        <p class="sub-total">sub total: $<span><?php echo $sub_total = ($fetch_cart['qty']*$fetch_cart['price']) ?></span></p>
                        <button type="submit" name="delete_item" class="btn" onclick="return confirm('remove from cart');">delete</button>
                    </div>
                </div>
            </form>

            <?php
                    $grand_total += $sub_total;
                    }else{
                        echo '
                        <div class="empty">
                            <p>No Product Was Found!</p>
                        </div>
                        ';
                    }
                }
            }else{
                echo '
                <div class="empty">
                    <p>No Product Placed Yet!</p>
                </div>
                ';
            }
            ?>
        </div>
        <?php if($grand_total != 0){ ?>
            <div class="cart-total">
                <p>total amount payable : <span> $<?php echo $grand_total ?>/-</span></p>
                <div class="button">
                    <form action="" method="POST">
                        <button type="submit" name="empty_cart" class="btn" onclick="return confirm('are you sure to empty your cart');">empty cart</button>
                    </form>
                    <a href="checkout.php" class="btn">proceed to checkout</a>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>