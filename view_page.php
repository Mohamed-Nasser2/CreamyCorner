<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

$pid = $_GET['pid'];
include 'components/add_to_wishlist.php';
include 'components/add_cart.php';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - product detail Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Product Detail</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Product Detail </span>
        </div>
    </div>

    <section class="view_page">
        <div class="heading">
            <h1>Product Detail</h1>
            <img src="image/separator-img.png" alt="">
        </div>
        <?php
        if(isset($_GET['pid'])){
            $pid = $_GET['pid'];
            $select_product = $con->prepare("SELECT * FROM products WHERE id = ?");
            $select_product->execute(array($pid));

            if($select_product->rowCount() > 0){
                while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
        ?>

        <form action="" method="POST" class="box">
            <div class="img-box">
                <img src="uploaded_files/<?php echo $fetch_product['image']; ?>" alt="">
            </div>
            <div class="detail">
                <?php if($fetch_product['stock'] > 9){ ?>
                    <span class="stock" style="color: green;">in stock</span>
                <?php }elseif($fetch_product['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">out of stock</span>
                <?php }else{ ?>
                    <span class="stock" style="color: red;">Hurry only, <?php echo $fetch_product['stock'] ?> left</span>
                <?php } ?>
                <p class="price">$<?php echo $fetch_product['price'] ?>/-</p>
                <div class="name"><?php echo $fetch_product['name'] ?></div>
                <p class="product-detail"><?php echo $fetch_product['product_detail'] ?></p>
                <input type="hidden" name="product_id" value="<?php echo $fetch_product['id'] ?>">
                <div class="button">
                    <button type="submit" name="add_to_wishlist" class="btn">add to wishlist <i class="bx bx-heart"></i></button>
                    <input type="hidden" name="qty" value="1" min="0" class="quantity">
                    <button type="submit" name="add_to_cart" class="btn">add to cart <i class="bx bx-cart"></i></button>
                </div>
            </div>
        </form>

        <?php
                }
            }
        }
        ?>
    </section>

    <div class="products">
        <div class="heading">
            <h1>similar products</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <?php include 'components/shop.php' ?>
    </div>
    
    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>