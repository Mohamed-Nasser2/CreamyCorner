<div class="products">
        
        <div class="box-container">
            <?php
            $select_product = $con->prepare("SELECT * FROM products WHERE status = ? LIMIT 6");
            $select_product->execute(array('active'));

            if($select_product->rowCount() > 0){
                while($fetch_products = $select_product->fetch(PDO::FETCH_ASSOC)){
            ?>

            <form action="" method="POST" class="box <?php if($fetch_products['stock'] == 0) { echo "disabled";} ?>">
                <img src="uploaded_files/<?php echo $fetch_products['image'] ?>" class="image">
                <?php if($fetch_products['stock'] > 9 ){ ?>
                    <span class="stock" style="color: green;">In stock</span>
                <?php }elseif($fetch_products['stock'] == 0){ ?>
                    <span class="stock" style="color: red;">Out of stock</span>
                <?php }else{ ?>
                    <span class="stock" style="color: red;">Hurry, Only <?php echo $fetch_products['stock'] ?></span>
                <?php } ?>
                <div class="content">
                    <img src="image/shape-19.png" alt="" class="shap">
                    <div class="button">
                        <div><h3 class="name"><?php echo $fetch_products['name'] ?></h3></div>
                        <div>
                            <button type="submit" name="add_to_cart"><i class="bx bx-cart"></i></button>
                            <button type="submit" name="add_to_wishlist"><i class="bx bx-heart"></i></button>
                            <a href="view_page.php?pid=<?php echo $fetch_products['id'] ?>" class="bx bxs-show"></a>
                        </div>
                    </div>
                    <p class="price">price $<?php echo $fetch_products['price'] ?></p>
                    <input type="hidden" name="product_id" value="<?php echo $fetch_products['id'] ?>">
                    <div class="flex-btn">
                        <a href="checkout.php?get_id=<?php echo $fetch_products['id'] ?>" class="btn">buy now</a>
                        <input type="number" name="qty" required min="1" value="1" max="99" maxlength="2" class="qty box">
                    </div>
                </div>
            </form>

            <?php
                }
            }else{
                echo '
                <div class="empty">
                    <p>No Order Placed Yet!</p>
                </div>
                ';
            }
            ?>
        </div>
    </div>