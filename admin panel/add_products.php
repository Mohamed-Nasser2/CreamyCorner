<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

//add Product In database
if(isset($_POST['publish'])){
    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name , FILTER_SANITIZE_STRING);

    $price = $_POST['price'];
    $price = filter_var($price , FILTER_SANITIZE_STRING);
    
    $description = $_POST['description'];
    $description = filter_var($description , FILTER_SANITIZE_STRING);
    
    $stock = $_POST['stock'];
    $stock = filter_var($stock , FILTER_SANITIZE_STRING);
    $status = 'active';

    $image = $_FILES['image']['name'];
    $image = filter_var($image , FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/'.$image;

    $select_image = $con->prepare("SELECT * FROM products WHERE image = ? AND seller_id = ?");
    $select_image->execute(array($image , $seller_id));

    if(isset($image)){
        if($select_image->rowCount() > 0){
            $warning_msg[] = 'Image Name Reapeated';
        }elseif($image_size > 2000000){
            $warning_msg[] = 'Image Size Is Too Large';
        }else{
            move_uploaded_file($image_tmp_name , $image_folder);
        }
    }else{
        $image='';
    }
    if($select_image->rowCount() > 0 AND $image != ''){
        $warning_msg[] = 'Please Rename Your Image';
    }else{
        $insert_product = $con->prepare("INSERT INTO products (id , seller_id , name , price , image , stock , product_detail , status)
                                        VALUES(? , ? , ? , ? , ? , ? , ? , ?)");
        $insert_product->execute(array($id , $seller_id , $name , $price , $image , $stock , $description , $status));
        $success_msg[] = 'Product Insert Successfully';
    }
}


if(isset($_POST['draft'])){
    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name , FILTER_SANITIZE_STRING);

    $price = $_POST['price'];
    $price = filter_var($price , FILTER_SANITIZE_STRING);
    
    $description = $_POST['description'];
    $description = filter_var($description , FILTER_SANITIZE_STRING);
    
    $stock = $_POST['stock'];
    $stock = filter_var($stock , FILTER_SANITIZE_STRING);
    $status = 'deactive';

    $image = $_FILES['image']['name'];
    $image = filter_var($image , FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_files/'.$image;

    $select_image = $con->prepare("SELECT * FROM products WHERE image = ? AND seller_id = ?");
    $select_image->execute(array($image , $seller_id));

    if(isset($image)){
        if($select_image->rowCount() > 0){
            $warning_msg[] = 'Image Name Reapeated';
        }elseif($image_size > 2000000){
            $warning_msg[] = 'Image Size Is Too Large';
        }else{
            move_uploaded_file($image_tmp_name , $image_folder);
        }
    }else{
        $image='';
    }
    if($select_image->rowCount() > 0 AND $image != ''){
        $warning_msg[] = 'Please Rename Your Image';
    }else{
        $insert_product = $con->prepare("INSERT INTO products (id , seller_id , name , price , image , stock , product_detail , status)
                                        VALUES(? , ? , ? , ? , ? , ? , ? , ?)");
        $insert_product->execute(array($id , $seller_id , $name , $price , $image , $stock , $description , $status));
        $success_msg[] = 'Product Saved As Draft Successfully';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Seller Registrarion Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="post-editor">
            <div class="heading">
                <h1>Add Product</h1>
                <img src="../image/separator-img.png">
            </div>

            <div class="form-container">
                <form action="" method="POST" enctype="multipart/form-data" class="register">
                    <div class="input-field">
                        <p>Product Name <span>*</span></p>
                        <input type="text" name="name" maxlength="100" placeholder="Add Product Name" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Product Price <span>*</span></p>
                        <input type="number" name="price" maxlength="100" placeholder="Add Product Price" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Product Detail <span>*</span></p>
                        <textarea name="description" required maxlength="1000" placeholder="Add Product Detail" class="box"></textarea>
                    </div>
                    <div class="input-field">
                        <p>Product Stock <span>*</span></p>
                        <input type="number" name="stock" maxlength="10" min="0" max="999999999" placeholder="Add Product Stock" required class="box">
                    </div>
                    <div class="input-field">
                        <p>Product Image <span>*</span></p>
                        <input type="file" name="image" accept="image/*" required class="box">
                    </div>
                    <div class="flex-btn add-pro">
                        <input type="submit" name="publish" value="Add Product" class="btn">
                        <input type="submit" name="draft" value="Save As Draft" class="btn">
                    </div>
                </form>
            </div>
            
        </section>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="../js/admin_script.js?v=<?php echo time(); ?>"></script>

    <?php include "../components/alert.php"; ?>

</body>
</html>