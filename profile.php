<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = 'Location:login.php';
}

$select_orders = $con->prepare("SELECT * FROM orders WHERE user_id = ?");
$select_orders->execute(array($user_id));
$total_orders = $select_orders->rowCount();

$select_message = $con->prepare("SELECT * FROM message WHERE user_id = ?");
$select_message->execute(array($user_id));
$total_message = $select_message->rowCount();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - user Profile Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Profile</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Profile </span>
        </div>
    </div>

    <section class="profile">
        <div class="heading">
            <h1>Profile Detail</h1>
            <img src="image/separator-img.png">
        </div>
        <div class="details">
            <div class="user">
                <img src="uploaded_files/<?php echo $fetch_profile['image']; ?>">
                <h3><?php echo $fetch_profile['name']; ?></h3>
                <p>User</p>
                <a href="update.php" class="btn">Update Profile</a>
            </div>
            <div class="box-container">
                <div class="box">
                    <div class="flex">
                        <i class="bx bxs-folder-minus"></i>
                        <h3><?php echo $total_orders ?></h3>
                    </div>
                    <a href="order.php" class="btn">View Orders</a>
                </div>
                <div class="box">
                    <div class="flex">
                        <i class="bx bxs-chat"></i>
                        <h3><?php echo $total_message ?></h3>
                    </div>
                    <a href="message.php" class="btn">View Message</a>
                </div>
            </div>
        </div>
    </section>


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>