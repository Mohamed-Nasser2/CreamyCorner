<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}

if(isset($_POST['send_message'])){
    if($user_id != ''){
        $id = unique_id();
        $name = $_POST['name'];
        $name = filter_var($name , FILTER_SANITIZE_STRING);

        $email = $_POST['email'];
        $email = filter_var($email , FILTER_SANITIZE_STRING);

        $subject = $_POST['subject'];
        $subject = filter_var($subject , FILTER_SANITIZE_STRING);

        $message = $_POST['message'];
        $message = filter_var($message , FILTER_SANITIZE_STRING);

        $verify_message = $con->prepare("SELECT * FROM message WHERE user_id = ? AND name = ? AND email = ? AND subject = ? AND message = ?");
        $verify_message->execute(array($user_id , $name , $email , $subject , $message)); 

        if($verify_message->rowCount() > 0){
            $warning_msg[] = 'message already exist';
        }else{
            $insert_message = $con->prepare("INSERT INTO message (id , user_id , name , email , subject , message) 
                                            VALUES(? , ? , ? , ? , ? , ?)");
            $insert_message->execute(array($id , $user_id , $name , $email , $subject , $message));

            $success_msg[] = 'Comment Added';
        }
    }else{
        $warning_msg[] = 'Please Login First';
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Contact Us Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Contact Us</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Contact Us </span>
        </div>
    </div>

    <div class="services">
        <div class="heading">
            <h1>our services</h1>
            <p>Just A Few Click To Make The Reservation online For Saving Your Time And Money</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <div class="box">
                <img src="image/0.png" alt="">
                <div>
                    <h1>free shipping fast</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
            </div>
            <div class="box">
                <img src="image/1.png" alt="">
                <div>
                    <h1>money back & guarantee</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
            </div>
            <div class="box">
                <img src="image/2.png" alt="">
                <div>
                    <h1>online support 24/7</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-container">
        <div class="heading">
            <h1>drop us a line</h1>
            <p>Just A Few Click To Make The Reservation online For Saving Your Time And Money</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <form action="" method="POST" class="register">
            <div class="input-field">
                <label>name <sup>*</sup></label>
                <input type="text" name="name" required placeholder="enter your name" class="box">
            </div>
            <div class="input-field">
                <label>email <sup>*</sup></label>
                <input type="email" name="email" required placeholder="enter your email" class="box">
            </div>
            <div class="input-field">
                <label>subject <sup>*</sup></label>
                <input type="text" name="subject" required placeholder="reason..." class="box">
            </div>
            <div class="input-field">
                <label>comment <sup>*</sup></label>
                <textarea name="message"cols="30" rows="10" required placeholder="" class="box"></textarea>
            </div>
            <button type="submit" name="send_message" class="btn">send message</button>
        </form>
    </div>

    <div class="address">
        <div class="heading">
            <h1>our contact details</h1>
            <p>Just A Few Click To Make The Reservation online For Saving Your Time And Money</p>
            <img src="image/separator-img.png" alt="">
        </div>
        <div class="box-container">
            <div class="box">
                <i class="bx bxs-map-alt"></i>
                <div>
                    <h4>address</h4>
                    <p>Suez, Egypt <br> Africa , Egypt</p>
                </div>
            </div>
            <div class="box">
                <i class="bx bxs-phone-incoming"></i>
                <div>
                    <h4>phone number</h4>
                    <p>01205805123</p>
                    <p>01205805123</p>
                </div>
            </div>
            <div class="box">
                <i class="bx bxs-envelope"></i>
                <div>
                    <h4>email</h4>
                    <p>mohamedn1345@gmail.com</p>
                    <p>mohamedn1345@gmail.com</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>