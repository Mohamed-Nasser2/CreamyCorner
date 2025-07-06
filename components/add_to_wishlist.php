<?php

if(isset($_POST['add_to_wishlist'])){
    if($user_id != ''){
        $id = unique_id();
        $product_id = $_POST['product_id'];

        $verify_wishlist = $con->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $verify_wishlist->execute(array($user_id , $product_id));

        $cart_num = $con->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $cart_num->execute(array($user_id , $product_id));

        if($verify_wishlist->rowCount() > 0){
            $warning_msg[] = 'product already exist in your wishlist';
        }elseif($user_id != ''){
            $select_price = $con->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $select_price->execute(array($product_id));
            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

            $insert_wishlist = $con->prepare("INSERT INTO wishlist (id , user_id , product_id , price)
                                                VALUES (? , ? , ? , ?)");
            $insert_wishlist->execute(array($id , $user_id , $product_id , $fetch_price['price']));

            $success_msg[] = 'product added to your wishlist';
        }
    }else{
        $warning_msg[] = 'Please Login First';
    }
}

?>