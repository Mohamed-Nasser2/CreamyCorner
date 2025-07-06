<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}


if (isset($_POST['submit'])){
    
    $email = $_POST['email'];
    $email = filter_var($email , FILTER_SANITIZE_STRING);
    
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass , FILTER_SANITIZE_STRING);

    $select_user = $con->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $select_user->execute(array($email , $pass));

    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if($select_user->rowCount() > 0){
        setcookie('user_id' , $row['id'] , time() + 60*60*24*30 , '/');
        header("Location:home.php");
    }else{
        $warning_msg[] = 'Incorrect Email or Password';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - user login Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Login</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Login </span>
        </div>
    </div>

    
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="login">
            <h3>Login Now</h3>

                <div class="input-field">
                    <p>Your Email <span>*</span></p>
                    <input type="email" name="email" placeholder="Enter Your Email" maxlength="50" required class="box"/>
                </div>
                <div class="input-field">
                    <p>Your Password <span>*</span></p>
                    <input type="password" name="pass" placeholder="Enter Your Password" maxlength="50" required class="box" autocomplete="new-password"/>
                </div>
                <p class="link">Do not have an account? <a href="register.php">Register Now</a></p>
                <input type="submit" name="submit" value="login now" class="btn"/>
        </form>
    </div>


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>