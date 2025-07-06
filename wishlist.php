<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = 'Location:login.php';
}


include 'components/add_cart.php';

//remove product from wishlist

if(isset($_POST['delete_item'])){
    $wishlist_id = $_POST['wishlist_id'];
    $wishlist_id = filter_var($wishlist_id , FILTER_SANITIZE_STRING);

    $verify_delete = $con->prepare("SELECT * FROM wishlist WHERE id = ?");
    $verify_delete->execute(array($wishlist_id));

    if($verify_delete->rowCount() > 0){
        $delete_wishlist_id = $con->prepare("DELETE FROM wishlist WHERE id = ?");
        $delete_wishlist_id->execute(array($wishlist_id));
        $success_msg[] = 'item removed from wishlist';
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
    <title>Blue Sky Summer - wishlist Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>My Wishlist</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>My Wishlist </span>
        </div>
    </div>

    <div class="products">
        <div class="heading">
            <h1>My Wishlist</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <?php
            $grand_total = 0;

            $select_wishlist = $con->prepare("SELECT * FROM wishlist WHERE user_id = ?");
            $select_wishlist->execute(array($user_id));
            if($select_wishlist->rowCount() > 0){
                while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){
                    $select_product = $con->prepare("SELECT * FROM products WHERE id = ?");
                    $select_product->execute(array($fetch_wishlist['product_id']));

                    if($select_product->rowCount() > 0){
                        $fetch_product = $select_product->fetch(PDO::FETCH_ASSOC);
            ?>

            <form action="" method="POST" class="box <?php if($fetch_product['stock'] == 0){ echo "disabled";} ?>">
                <input type="hidden" name="wishlist_id" value="<?php echo $fetch_wishlist['id'] ?>">
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
                    <div class="button">
                        <div><h3><?php echo $fetch_product['name'] ?></h3></div>
                        <div>
                            <button type="submit" name="add_to_cart"> <i class="bx bx-cart"></i></button>
                            <a href="view_page.php?pid=<?php echo $fetch_product['id'] ?>" class="bx bxs-show"></a>
                            <button type="submit" name="delete_item" onclick="return confirm('remove from wishlist')"> <i class="bx bx-x"></i></button>
                        </div>
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo $fetch_product['id'] ?>">
                    <div class="flex">
                        <p class="price">price $<?php echo $fetch_product['price']?>/-</p>
                    </div>
                    <div class="flex">
                        <input type="hidden" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty">
                        <a href="checkout.php?get_id=<?php echo $fetch_product['id'] ?>" class="btn">buy now</a>
                    </div>
                </div>
            </form>

            <?php
                    $grand_total += $fetch_wishlist['price'];
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
    </div>
    


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>