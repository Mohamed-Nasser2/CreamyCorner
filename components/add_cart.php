<?php

if(isset($_POST['add_to_cart'])){
    if($user_id != ''){
        $id = unique_id();
        $product_id = $_POST['product_id'];

        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);

        $verify_cart = $con->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $verify_cart->execute(array($user_id , $product_id));

        $max_cart_items = $con->prepare("SELECT * FROM cart WHERE user_id = ?");
        $max_cart_items->execute(array($user_id));

        if($verify_cart->rowCount() > 0){
            $warning_msg[] = 'product already exist in your cart';
        }elseif($max_cart_items->rowCount() >20){
            $warning_msg[] = 'Your cart is full';
        }else{
            $select_price = $con->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
            $select_price->execute(array($product_id));
            $fetch_price = $select_price->fetch(PDO::FETCH_ASSOC);

            $insert_cart = $con->prepare("INSERT INTO cart (id , user_id , product_id , product_name , price , qty)
                                            VALUES (? , ? , ? , ? , ? , ?)");
            $insert_cart->execute(array($id , $user_id , $product_id , $fetch_price['name'] , $fetch_price['price'] , $qty));

            $success_msg[] = 'product added to your cart';
        }
    }else{
        $warning_msg[] = 'Please Login First';
    }
}

?>