<?php
session_start();
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
}


if(isset($_POST['submit'])){
    $select_user = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
    $select_user->execute(array($user_id));
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

    $prev_pass = $fetch_user['password'];
    $prev_image = $fetch_user['image'];

    $name = $_POST['name'];
    $name = filter_var($name , FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email , FILTER_SANITIZE_STRING);

     //update name 
    if(!empty($name)){
        $update_name = $con->prepare("UPDATE users SET name = ? WHERE id = ?");
        $update_name->execute(array($name , $user_id));
        $success_msg[] = 'Username Updated Successfully';
    }

     //update email 
    if(!empty($email)){
        $select_email = $con->prepare("SELECT * FROM users WHERE id = ? AND email = ?");
        $select_email->execute(array($user_id , $email));

        if($select_email->rowCount() > 0){
            $warning_msg[] = 'Email Alredy Exist';
        }else{
            $update_email = $con->prepare("UPDATE users SET email = ? WHERE id = ?");
            $update_email->execute(array($email , $user_id));
            $success_msg[] = 'Email Updated Successfully';
        }
    }

    //update image
    $image = $_FILES['image']['name'];
    $image = filter_var($image , FILTER_SANITIZE_STRING);
    $ext = pathinfo($image , PATHINFO_EXTENSION);
    $rename = unique_id().'.'.$ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/'.$rename;

    if(!empty($image)){
        if($image_size > 2000000){
            $warning_msg[] = 'Image Size Is Too Large';
        }else{
            $update_image = $con->prepare("UPDATE users SET image = ? WHERE id = ?");
            $update_image->execute(array($rename , $user_id));
            move_uploaded_file($image_tmp_name , $image_folder);

            if($prev_image != '' AND $prev_image != $rename){
                unlink('uploaded_files/'.$prev_image);
            }
            $success_msg[] = 'Image Updated Successfully';
        }
    }

    //update password
    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass , FILTER_SANITIZE_STRING);

    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass , FILTER_SANITIZE_STRING);

    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass , FILTER_SANITIZE_STRING);

    if($old_pass != $empty_pass){
        if($old_pass != $prev_pass){
            $warning_msg[] = 'Old Password Not Matched';
        }elseif($new_pass != $cpass){
            $warning_msg[] = 'Password Not Matched';
        }else{
            if($new_pass != $empty_pass){
                $update_pass = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_pass->execute(array($cpass , $user_id));
                $success_msg[] = 'Password Updated Successfully';
            }else{
                $warning_msg[] = 'Please Enter a New Password';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Sky Summer - Update profile Page</title>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
    
    <?php include "components/user_header.php"; ?>

    <div class="banner">
        <div class="detail">
            <h1>Update Profile</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. <br> Lorem Ipsum has been the industry's standard dummy text.</p>
            <span><a href="home.php">home</a> <i class="bx bx-right-arrow-alt"></i>Update Profile </span>
        </div>
    </div>

    
    <section class="form-container">
            <div class="heading">
                <h1>Update Profile Details</h1>
                <img src="image/separator-img.png">
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="register">
                <div class="img-box">
                    <img src="uploaded_files/<?php echo $fetch_profile['image']; ?>">
                </div>
                <div class="flex">
                    <div class="col">
                        <div class="input-field">
                            <p>Your Name <span>*</span></p>
                            <input type="text" name="name" placeholder="<?php echo $fetch_profile['name'] ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Your Email <span>*</span></p>
                            <input type="email" name="email" placeholder="<?php echo $fetch_profile['email'] ?>" class="box">
                        </div>
                        <div class="input-field">
                            <p>Select Pic <span>*</span></p>
                            <input type="file" name="image" accept="image/*" class="box">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-field">
                            <p>Old Password <span>*</span></p>
                            <input type="password" name="old_pass" placeholder="Enter Your Old Password" class="box" autocomplete="new-password">
                        </div>
                        <div class="input-field">
                            <p>New Password <span>*</span></p>
                            <input type="password" name="new_pass" placeholder="Enter Your New Password" class="box">
                        </div>
                        <div class="input-field">
                            <p>Confirm Password <span>*</span></p>
                            <input type="password" name="cpass" placeholder="Confirm your Password" class="box">
                        </div>
                    </div>
                </div>
                <input type="submit" name="submit" value="Update Profile" class="btn">
            </form>
            
        </section>


    <?php include 'components/footer.php'; ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="js/user_script.js?v=<?php echo time(); ?>"></script>

    <?php include "components/alert.php"; ?>
</body>
</html>