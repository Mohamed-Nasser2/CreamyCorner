<?php

include "../components/connect.php";

if(isset($_COOKIE['seller_id'])){
    $seller_id = $_COOKIE['seller_id'];
}else{
    $seller_id = '';
    header('Location:login.php');
}

//delete message
if(isset($_POST['delete_mg'])){
    $delete_id = $_POST['delete_id'];
    $delete_id = filter_var($delete_id , FILTER_SANITIZE_STRING);

    $verify_delete = $con->prepare("SELECT * FROM message WHERE id = ?");
    $verify_delete->execute(array($delete_id));

    if($verify_delete->rowCount() > 0){
        $delete_msg = $con->prepare("DELETE FROM message WHERE id = ?");
        $delete_msg->execute(array($delete_id));
        $success_msg[] = 'Message Deleted Successfully';
    }else{
        $warning_msg[] = 'Message Already Deleted';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Admin Dashboard Page</title>
    <link rel="stylesheet" type="text/css" href="../css/admin_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <div class="main-container">
        <?php include "../components/admin_header.php"; ?>
        <section class="message-container">
            <div class="heading">
                <h1>Unread Message</h1>
                <img src="../image/separator-img.png">
            </div>
        
            <div class="box-container">
                <?php
                $select_message = $con->prepare("SELECT * FROM message");
                $select_message->execute();
                if($select_message->rowCount() > 0){
                    while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
                ?>

                <div class="box">
                    <h3 class="name"><?php echo $fetch_message['name'] ?></h3>
                    <h4><?php echo $fetch_message['subject'] ?></h4>
                    <p><?php echo $fetch_message['message'] ?></p>
                    <form action="" method="POST">
                        <input type="hidden" name="delete_id" value="<?php echo $fetch_message['id'] ?>">
                        <input type="submit" name="delete_mg" value="Delete Message" class="btn" onclick="return confirm('Delete This Message')">
                    </form>
                </div>
                
                <?php
                    }
                }else{
                    echo '
                    <div class="empty">
                        <p>No Unread Message Yet!</p>
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