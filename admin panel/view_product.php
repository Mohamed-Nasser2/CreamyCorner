<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

//delete product

if(isset($_POST['delete'])){
    $p_id = $_POST['product_id'];
    $p_id = filter_var($p_id , FILTER_SANITIZE_STRING);

    $delete_product = $con->prepare("DELETE FROM products WHERE id = ?");
    $delete_product->execute(array($p_id));

    $success_msg[] = 'Product Deleted Successfully';
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

    <?php
    $status = isset($_GET['status']) ? $_GET['status'] : 'admin_order';

    if($status == 'deactive'){
    ?>

        <div class="main-container">
            <?php include "../components/admin_header.php"; ?>
            <section class="show-post">
                <div class="heading">
                    <h1>Your deactive Products</h1>
                    <img src="../image/separator-img.png">
                </div>

                <div class="box-container">
                    <?php
                    $select_products = $con->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
                    $select_products->execute(array($seller_id , 'deactive'));
                    if($select_products->rowCount() > 0){
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){

                        
                    ?>

                    <form action="" method="POST" class="box">
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <?php
                        if($fetch_products['image'] != ''){?>
                            <img src="../uploaded_files/<?php echo $fetch_products['image']; ?>" class="image">
                        <?php } ?>
                        <div class="status" style="color:red"><?php echo $fetch_products['status']; ?></div>
                        <div class="price">$<?php echo $fetch_products['price'] ?>/-</div>
                        <div class="content">
                            <img src="../image/shape-19.png" class="shap">
                            <div class="title"><?php echo $fetch_products['name'] ?></div>
                            <div class="flex-btn">
                                <a href="edit_product.php?id=<?php echo $fetch_products['id']; ?>" class="btn">Edit</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Delete This Product')">Delete</button>
                                <a href="read_product.php?post_id=<?php echo $fetch_products['id']; ?>" class="btn">Read</a>
                            </div>
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

    <?php }elseif($status == 'active'){ ?>
    
        <div class="main-container">
            <?php include "../components/admin_header.php"; ?>
            <section class="show-post">
                <div class="heading">
                    <h1>Your Products</h1>
                    <img src="../image/separator-img.png">
                </div>

                <div class="box-container">
                    <?php
                    $select_products = $con->prepare("SELECT * FROM products WHERE seller_id = ? AND status = ?");
                    $select_products->execute(array($seller_id , 'active'));
                    if($select_products->rowCount() > 0){
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){

                        
                    ?>

                    <form action="" method="POST" class="box">
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <?php
                        if($fetch_products['image'] != ''){?>
                            <img src="../uploaded_files/<?php echo $fetch_products['image']; ?>" class="image">
                        <?php } ?>
                        <div class="status" style="color:limegreen"><?php echo $fetch_products['status']; ?></div>
                        <div class="price">$<?php echo $fetch_products['price'] ?>/-</div>
                        <div class="content">
                            <img src="../image/shape-19.png" class="shap">
                            <div class="title"><?php echo $fetch_products['name'] ?></div>
                            <div class="flex-btn">
                                <a href="edit_product.php?id=<?php echo $fetch_products['id']; ?>" class="btn">Edit</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Delete This Product')">Delete</button>
                                <a href="read_product.php?post_id=<?php echo $fetch_products['id']; ?>" class="btn">Read</a>
                            </div>
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
    <?php }elseif($status == 'all') { ?> 

        <div class="main-container">
            <?php include "../components/admin_header.php"; ?>
            <section class="show-post">
                <div class="heading">
                    <h1>Your Products</h1>
                    <img src="../image/separator-img.png">
                </div>

                <div class="box-container">
                    <?php
                    $select_products = $con->prepare("SELECT * FROM products WHERE seller_id = ?");
                    $select_products->execute(array($seller_id));
                    if($select_products->rowCount() > 0){
                        while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){

                        
                    ?>

                    <form action="" method="POST" class="box">
                        <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                        <?php
                        if($fetch_products['image'] != ''){?>
                            <img src="../uploaded_files/<?php echo $fetch_products['image']; ?>" class="image">
                        <?php } ?>
                        <div class="status" style="color:<?php if($fetch_products['status'] == 'active'){ echo 'limegreen'; }elseif($fetch_products['status'] == 'deactive'){ echo 'red'; }else{ echo 'orange'; } ?>"><?php echo $fetch_products['status']; ?></div>
                        <div class="price">$<?php echo $fetch_products['price'] ?>/-</div>
                        <div class="content">
                            <img src="../image/shape-19.png" class="shap">
                            <div class="title"><?php echo $fetch_products['name'] ?></div>
                            <div class="flex-btn">
                                <a href="edit_product.php?id=<?php echo $fetch_products['id']; ?>" class="btn">Edit</a>
                                <button type="submit" name="delete" class="btn" onclick="return confirm('Delete This Product')">Delete</button>
                                <a href="read_product.php?post_id=<?php echo $fetch_products['id']; ?>" class="btn">Read</a>
                            </div>
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

    <?php }else{
        header("Location: dashboard.php");
        exit();
    } ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>