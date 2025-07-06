<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Registered sellers Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="user-container">
            <div class="heading">
                <h1>Registered Sellers</h1>
                <img src="../image/separator-img.png">
            </div>
        
            <div class="box-container">
                <?php
                $select_users = $con->prepare("SELECT * FROM sellers");
                $select_users->execute();

                if($select_users->rowCount() > 0){
                    while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                        $user_id = $fetch_users['id'];
                ?>

                <div class="box">
                    <img src="../uploaded_files/<?php echo $fetch_users['image'] ?>">
                    <p>User Id : <span><?php echo $user_id; ?></span></p>
                    <p>User Name : <span><?php echo $fetch_users['name']; ?></span></p>
                    <p>User Email : <span><?php echo $fetch_users['email']; ?></span></p>
                </div>

                <?php
                    }
                }else{
                    echo '
                    <div class="empty">
                        <p>No Sellers Registered Yet!</p>
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