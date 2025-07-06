<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

include 'components/add_to_wishlist.php';
include 'components/add_cart.php';




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - our shop Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Our Shop</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Our Shop </span>
        </div>
    </div>

    <div class="products">
        <div class="heading">
            <h1>Our Latest Flavoure</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="box-container">
            <?php
            $select_product = $con->prepare("SELECT * FROM products WHERE status = ?");
            $select_product->execute(array('active'));

            if($select_product->rowCount() > 0){
                while($fetch_products = $select_product->fetch(PDO::FETCH_ASSOC)){
            ?>

            <form action="" method="POST" class="box <?php if($fetch_products['stock'] == 0) { echo "disabled";} ?>">
                <img src="uploaded_files/<?php echo $fetch_products['image'] ?>" class="image">
                <?php if($fetch_products['stock'] > 9 ){ ?>
                    <span class="stock" style="color: green;">In stock</span>
                <?php }elseif($fetch_products['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">Out of stock</span>
                <?php }else{ ?>
                    <span class="stock" style="color: red;">Hurry, Only <?php echo $fetch_products['stock'] ?></span>
                <?php } ?>
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <div class="button">
                        <div><h3 class="name"><?php echo $fetch_products['name'] ?></h3></div>
                        <div>
                            <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                            <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                            <a href="view_page.php?pid=<?php echo $fetch_products['id'] ?>" class="bx bxs-show"></a>
                        </div>
                    </div>
                    
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['id'] ?>">
                    <div class="flex-btn">
                        <a href="checkout.php?get_id=<?php echo $fetch_products['id'] ?>" class="btn">buy now</a>
                        <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                        
                        <p class="price">price $<?php echo $fetch_products['price'] ?></p>
                        
                    </div>
                </div>
            </form>

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
    </div>
    


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>