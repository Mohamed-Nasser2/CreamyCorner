<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

$get_id = $_GET['post_id'];

//deleted Product

if(isset($_POST['delete'])){
    $p_id = $_POST['product_id'];
    $p_id = filter_var($p_id , FILTER_SANITIZE_STRING);

    $delete_image = $con->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
    $delete_image->execute(array($p_id , $seller_id));

    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if($fetch_delete_image[''] != ''){
        unlink('../uploaded_files/'.$fetch_delete_image['image']);
    }

    $delete_product = $con->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $delete_product->execute(array($p_id , $seller_id));
    header("Location:view_product.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Show Products Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="read-post">
            <div class="heading">
                <h1>Products Detail</h1>
                <img src="../image/separator-img.png">
            </div>

            <div class="box-container">
                <?php
                $select_product = $con->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
                $select_product->execute(array($get_id , $seller_id));
                if($select_product->rowCount() > 0){
                    while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){

                ?>
                
                <form action="" method="POST" class="box">
                    <input type="hidden" name="product_id" value="<?php echo $fetch_product['id'] ?>">
                    <div class="status" style="color:<?php if($fetch_product['status'] == 'active'){echo 'limegreen';}else{echo 'coral';} ?>"><?php echo $fetch_product['status'] ?></div>
                    <?php if($fetch_product['image'] != ''){ ?>
                        <img src="../uploaded_files/<?php echo $fetch_product['image']; ?>" class="image">
                    <?php } ?>
                    <div class="price">$<?php echo $fetch_product['price'] ?>/-</div>
                    <div class="title"><?php echo $fetch_product['name'] ?></div>
                    <div class="content"><?php echo $fetch_product['product_detail'] ?></div>
                    <div class="flex-btn">
                        <a href="edit_product.php?id=<?php echo $fetch_product['id'] ?>" class="btn">Edit</a>
                        <button type="submit" name="delete" class="btn" onclick="return confirm('Delete This Product')">Delete</button>
                        <a href="view_product.php?post_id=<?php echo $fetch_product['id'] ?>" class="btn">Go Back</a>
                    </div>
                </form>

                <?php
                    }
                }else{
                    echo '
                    <div class="empty">
                        <p>No Products Added Yet ! <br> <a href="add_products.php" class="btn" style="margin-top:1.5rem;">Add Product</a></p>
                    </div>
                    ';
                }
                ?>
            </div>
        </section>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>