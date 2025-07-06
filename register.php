<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}


if (isset($_POST['submit'])){
    $id = unique_id();
    $name = $_POST['name'];
    $name = filter_var($name , FILTER_SANITIZE_STRING);
    
    $email = $_POST['email'];
    $email = filter_var($email , FILTER_SANITIZE_STRING);
    
    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass , FILTER_SANITIZE_STRING);
    
    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass , FILTER_SANITIZE_STRING);
    
    $image = $_FILES['image']['name'];
    $image = filter_var($image , FILTER_SANITIZE_STRING);
    $ext = pathinfo($image , PATHINFO_EXTENSION);
    $rename = unique_id() .'.'. $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/' . $rename;

    $select_seller = $con->prepare("SELECT * FROM users WHERE email = ?");
    $select_seller->execute(array($email));

    if($select_seller->rowCount() > 0){
        $warning_msg[] = 'Email Alredy Exist';
    }else{
        if($pass != $cpass){
            $warning_msg[] = 'Confirm Password Not Matched';
        }else{
            $insert_seller = $con->prepare("INSERT INTO 
                                                users(id , name , email , password , image)
                                            VALUES
                                                (? , ? , ? , ? , ?)");
            $insert_seller->execute(array(
                $id,
                $name,
                $email,
                $cpass,
                $rename
            ));
            move_uploaded_file($image_tmp_name , $image_folder);
            $success_msg[] = 'New User Registred ! Please Login Now ';
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - user registeration Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Register</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Register </span>
        </div>
    </div>

    
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data" class="register">
            <h3>register Now</h3>
            <div class="flex">
                <div class="col">
                    <div class="input-field">
                        <p>Your Name <span>*</span></p>
                        <input type="text" name="name" placeholder="Enter Your Name" maxlength="50" required class="box" autocomplete="off"/>
                    </div>
                    <div class="input-field">
                        <p>Your Email <span>*</span></p>
                        <input type="email" name="email" placeholder="Enter Your Email" maxlength="50" required class="box"/>
                    </div>
                </div>
                <div class="col">
                    <div class="input-field">
                        <p>Your Password <span>*</span></p>
                        <input type="password" name="pass" placeholder="Enter Your Password" maxlength="50" required class="box" autocomplete="new-password"/>
                    </div>
                    <div class="input-field">
                        <p>Confirm Password <span>*</span></p>
                        <input type="password" name="cpass" placeholder="Confirm Your Password" maxlength="50" required class="box"/>
                    </div>
                </div>
            </div>
            <div class="input-field">
                <p>Your Profile <span>*</span></p>
                <input type="file" name="image" accept="image/*" required class="box"/>
            </div>
                <p class="link">Already have an account? <a href="login.php">Login Now</a></p>
                <input type="submit" name="submit" value="register now" class="btn"/>
        </form>
    </div>


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>