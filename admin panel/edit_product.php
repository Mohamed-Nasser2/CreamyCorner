<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

if(isset($_POST['update'])){
    $product_id = $_POST['product_id'];
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

    $name = $_POST['name'];
    $name = filter_var($name , FILTER_SANITIZE_STRING);

    $price = $_POST['price'];
    $price = filter_var($price , FILTER_SANITIZE_STRING);
    
    $description = $_POST['description'];
    $description = filter_var($description , FILTER_SANITIZE_STRING);
    
    $stock = $_POST['stock'];
    $stock = filter_var($stock , FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $status = filter_var($status , FILTER_SANITIZE_STRING);

    $update_product = $con->prepare("UPDATE products SET name = ? , price = ? , product_detail = ?, stock = ? , status = ? WHERE id = ?");
    $update_product->execute(array($name , $price , $description , $stock , $status , $product_id));

    $success_msg[] = "Product Updated";

    $old_image = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $image = filter_var($image , FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/'.$image;

    $select_image = $con->prepare("SELECT * FROM products WHERE image = ? AND seller_id = ?");
    $select_image->execute(array($image , $seller_id));

    if(!empty($image)){
        if($image_size > 2000000){
            $warning_msg[] = 'image size is too large';
        }elseif($select_image->rowCount() > 0){
            $warning_msg[] = 'Please Rename Your Image';
        }else{
            $update_image = $con->prepare("UPDATE products SET image = ? WHERE id = ?");
            $update_image->execute(array($image , $product_id));
            move_uploaded_file($image_tmp_name , $image_folder);
            if($old_image != $image && $old_image != ''){
                unlink('../uploaded_files/'.$old_image);
            }
            $success_msg[] = 'Image Update!';
        }
    }

}

//delete image

if(isset($_POST['delete_image'])){
    $empty_image = '';
    $product_id = $_POST['product_id'];
    $product_id = filter_var($product_id, FILTER_SANITIZE_STRING);

    $delete_image = $con->prepare("SELECT * FROM products WHERE id = ?");
    $delete_image->execute(array($product_id));
    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if($fetch_delete_image['image'] != ''){
        unlink('../uploaded_files/'.$fetch_delete_image['image']);
    }
    $unset_image = $con->prepare("UPDATE products SET image = ? WHERE id = ?");
    $unset_image->execute(array($empty_image , $product_id));
    $success_msg[] = 'Image Deleted Successfully';
}

//delete product

if(isset($_POST['delete_product'])){
    $product_id = $_POST['product_id'];
    $product_id = filter_var($product_id , FILTER_SANITIZE_STRING);

    $delete_image = $con->prepare("SELECT * FROM products WHERE id = ?");
    $delete_image->execute(array($product_id));
    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if ($fetch_delete_image['image'] != ''){
        unlink('../uploaded_files/'.$fetch_delete_image['image']);
    }

    $delete_product = $con->prepare("DELETE FROM products WHERE id = ?");
    $delete_product->execute(array($product_id));

    $success_msg[] = 'Product Deleted Successfully';

    header('Location:view_product.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Edit Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="post-editor">
            <div class="heading">
                <h1>Edit Product</h1>
                <img src="../image/separator-img.png">
            </div>
            <div class="form-container">
                <?php
                if(isset( $_GET['id'])){
                $product_id = $_GET['id'];
                $select_product = $con->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
                $select_product->execute(array($product_id , $seller_id));
                if($select_product->rowCount() > 0){
                    while($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)){
                ?>

                <div class="form-container">
                    <form action="" method="POST" enctype="multipart/form-data" class="register">
                        <input type="hidden" name="old_image" value="<?php echo $fetch_product['image'] ?>">
                        <input type="hidden" name="product_id" value="<?php echo $fetch_product['id'] ?>">
                        <div class="input-field">
                            <p>Product Status <span>*</span></p>
                            <select name="status" class="box">
                                <option value="<?php echo $fetch_product['status']; ?>" selected><?php echo $fetch_product['status'] ?></option>
                                <option value="active">active</option>
                                <option value="deactive">deactive</option>
                            </select>
                        </div>
                        <div class="input-field">
                            <p>Product Name <span>*</span></p>
                            <input type="text" name="name" value="<?php echo $fetch_product['name']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Product Price <span>*</span></p>
                            <input type="number" name="price" value="<?php echo $fetch_product['price']; ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Product Description <span>*</span></p>
                            <textarea name="description" class="box"><?php echo $fetch_product['product_detail']; ?></textarea>
                        </div>
                        <div class="input-field">
                            <p>Product Stock <span>*</span></p>
                            <input type="number" name="stock" value="<?php echo $fetch_product['stock']; ?>" class="box" min="0" max="9999999999" maxlength="10">
                        </div>
                        <div class="input-field">
                            <p>Product Image <span>*</span></p>
                            <input type="file" name="image" accept="image/*" class="box">
                            <?php if($fetch_product['image'] != ''){?>
                                <img src="../uploaded_files/<?php echo $fetch_product['image']; ?>" class="image">
                                <div class="flex-btn">
                                    <input type="submit" name="delete_image" class="btn" value="Delete Image">
                                    <a href="view_product.php" class="btn" style="width:49%; text-align: center; height: 3rem; margin-top: .7rem">Go Back</a>
                                </div>
                            <?php }?>
                        </div>
                        <div class="flex-btn">
                            <input type="submit" name="update" value="Update Product" class="btn">
                            <input type="submit" name="delete_product" value="Delete Product" class="btn">
                        </div>
                    </form>
                </div>

                <?php
                    }
                }else{
                    echo '
                    <div class="empty">
                        <p>No Products Added Yet ! </p>
                    </div>
                    ';
                
                ?>
                <br><br>
                <div class="flex-btn">
                    <a href="view_product.php" class="btn">View Product</a>
                    <a href="add_products.php" class="btn">Add Product</a>
                </div>
                <?php } 
                }else{
                    echo '
                    <div class="empty">
                        <p>No Products Added Yet ! </p>
                    </div>
                    ';
                ?>
                <br><br>
                <div class="flex-btn">
                    <a href="view_product.php" class="btn">View Product</a>
                    <a href="add_products.php" class="btn">Add Product</a>
                </div>

                <?php } ?>
            </div>
        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>