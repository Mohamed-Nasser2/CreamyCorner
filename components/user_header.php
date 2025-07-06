<header class="header">
    <section class="flex">
        <a href="home.php" class="logo"><img src="image/logo.png" width="130px"></a>
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about-us.php">About us</a>
            <a href="menu.php">Shop</a>
            <a href="order.php">Order</a>
            <a href="contact.php">Contact us</a>
        </nav>
        <form action="search_product.php" method="POST" class="search-form">
            <input type="text" name="search_product" placeholder="Search Product...." required maxlength="100">
            <button type="submit" class="bx bx-search-alt-2" id="search_product_btn"></button>
        </form>
        <div class="icons">
            <div class="bx bx-list-plus" id="menu-btn"></div>
            <div class="bx bx-search-alt-2" id="search-btn"></div>

            <?php
            $count_wishlist_item = $con->prepare("SELECT * FROM wishlist WHERE user_id = ?");
            $count_wishlist_item->execute(array($user_id));
            $total_wishlist_item = $count_wishlist_item->rowCount();
            ?>

            <?php
            $count_cart_item = $con->prepare("SELECT * FROM cart WHERE user_id = ?");
            $count_cart_item->execute(array($user_id));
            $total_cart_item = $count_cart_item->rowCount();
            ?>

            <a href="wishlist.php"><i class="bx bx-heart"></i><sup><?php echo $total_wishlist_item ?></sup></a>
            <a href="cart.php"><i class="bx bx-cart"></i><sup><?php echo $total_cart_item ?></sup></a>
            <div class="bx bxs-user" id="user-btn"></div>
        </div>
        <div class="profile-detail">
            <?php
            $select_profile = $con->prepare("SELECT * FROM users WHERE id = ?");
            $select_profile->execute(array($user_id));
            
            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="uploaded_files/<?php echo $fetch_profile['image']; ?>">
            <h3 style="margin-bottom: 1rem;"><?php echo $fetch_profile['name']; ?></h3>
            <div class="flex-btn">
                <a href="profile.php" class="btn">View Profile</a>
                <a href="components/user_logout.php" onclick="return confirm('Logout From This Website');" class="btn">Logout</a>
            </div>
            <?php }else{ ?>
                <h3 style="margin-bottom: 1rem;">Please Login or Register</h3>
                <div class="flex-btn">
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn">Register</a>
                </div>
            <?php } ?>
        </div>
    </section>
</header>