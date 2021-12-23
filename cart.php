<?php
    include('php/conn.php');
    if(isset($_POST['moreItem']) || isset($_POST['lessItem']) || isset($_POST['removeItem'])){
        $idCart = filter_var($_POST['cartId'],FILTER_SANITIZE_NUMBER_INT);

        if(isset($_POST['moreItem'])){
            $query="UPDATE `Cart` SET `Quantity`=`Quantity`+1 WHERE `Id_cart`='$idCart';";

            if(!mysqli_query($conn, $query))
                echo mysqli_error($conn);

        }elseif(isset($_POST['lessItem'])){
            $query="UPDATE `Cart` SET `Quantity`=`Quantity`-1 WHERE `Id_cart`='$idCart';";

            if(!mysqli_query($conn, $query))
                echo mysqli_error($conn);

        }elseif(isset($_POST['removeItem'])){
            $query="DELETE FROM `Cart` WHERE `Id_cart`=$idCart;";

            if(!mysqli_query($conn, $query))
                echo mysqli_error($conn);
        }
    }

    function twoDigitsAfterDot($totalPrice){
        $totalExplode = explode('.',$totalPrice);

        if(isset($totalExplode[1])){
            if(strlen($totalExplode[1]) == 1)
                return $totalPrice = $totalPrice."0";
            }
        else
            return $totalPrice = $totalPrice.".00";
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/cart.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Coffee store</title>
</head>


<body>
    <?php include('header.php'); 
            include('php/redirect.php');?>

    <div class="container-fluid">
        <div class="row m-5">
            <div class="col-8">
                <div class="row mb-3">
                    <div class="col">
                        <h4><b>Shopping Cart</b></h4>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['user'])) {
                    $idUser = $_SESSION['user_id'];
                    $query = "SELECT * FROM `Cart`,`Coffee`,`Coffee_category`,`Coffee_company` WHERE `Id_user`=$idUser AND `Cart`.`Id_product` = `Coffee`.`Id_cfe` AND `Coffee`.`Id_cat` = `Coffee_category`.`Id_cat` AND `Coffee`.`Id_cmp` = `Coffee_company`.`Id_cmp`;";
                    $result = mysqli_query($conn, $query);
                    $totalItems = mysqli_num_rows($result);

                    // If cart is empty go to index page
                    if(empty($totalItems))
                        header('location:/Praktyki');

                    $totalPrice = 0;

                    while($itemInCart = mysqli_fetch_array($result)){
                        print('<div class="row border-top">
                        <form class="row main align-items-center" method="POST">
                                <input type="hidden" name="cartId" value="'.$itemInCart['Id_cart'].'">
                                <div class="col-2"><img class="img-fluid" src="img/products/'.$itemInCart['Src_cfe'].'"></div>
                                <div class="col-5">
                                    <div class="row text-muted">'.$itemInCart['Category_name'].'</div>
                                    <div class="row">'.$itemInCart['Company_name'].' '.$itemInCart['Name_cfe'].'</div>
                                </div>
                                <div class="col text-muted text-center">');
                                    if($itemInCart['Quantity'] <= 1)
                                        $isDisabled='disabled';
                                    else
                                        $isDisabled='';

                                    print('<input type="submit" class="formControlNoBorder text-muted" name="lessItem" value="-" '.$isDisabled.'/>');
                                    print('<span class="m-3">'.$itemInCart['Quantity'].'</span>');

                                    if($itemInCart['Quantity'] >= 10)
                                        $isDisabled='disabled';
                                    else
                                        $isDisabled='';
                                    print('<input type="submit" class="formControlNoBorder text-muted" name="moreItem" value="+" '.$isDisabled.'/> 
                                </div>
                                <div class="col text-center"> '.$itemInCart['Price_cfe'].' PLN</div>
                                <div class="col text-center"><input type="submit" class="formControlNoBorder" name="removeItem" value="&#10005;"/></div>
                        </form>
                    </div>');
                    $totalPrice += $itemInCart['Price_cfe'] * $itemInCart['Quantity'];
                    }
                }

                ?>
                <div class="border-top p-4"><a href="/Praktyki" class="backToShop"><span class="text-muted">&leftarrow; Back to shop</span></a></div>
            </div>
            <div class="col p-4">
                <div>
                    <h5><b>Summary</b></h5>
                </div>
                <hr>
                <div class="row">
                    <div class="col">ITEMS <?php echo $totalItems; ?></div>
                    <div class="col text-center" id="products" price="<?php echo $totalPrice; ?>"><?php echo twoDigitsAfterDot($totalPrice); ?> PLN</div>
                </div>
                <h6 class="mt-3">SHIPPING</h6> 
                <form method="POST" action="checkout.php">
                    <div class="row m-2">
                        <select class="form-select" id="shipping" onchange="updateTotalCosts();" name="shippingMethod">
                        <?php
                            $query = "SELECT * FROM `Shipping`;";
                            $result = mysqli_query($conn, $query);
                            while($shippingMethod = mysqli_fetch_array($result)){
                                print('<option value="'. $shippingMethod['Id_shipping'] .'" price="'. $shippingMethod['Price'] .'">'.$shippingMethod['Courier_name'] .' - '. $shippingMethod['Price'] .' PLN</option>');
                            }
                        ?>
                        </select>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">TOTAL PRICE</div>
                        <div class="col text-center"><span id="total"></span> PLN</div>
                    </div> 
                    <button type="submit" class="btn btn-primary mt-3 w-100" name="checkout">CHECKOUT</button>
                </form>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    function updateTotalCosts(){
        var select = document.getElementById('shipping');
        var shippingCost = parseFloat(select.options[select.selectedIndex].getAttribute('price'));

        var productCost = parseFloat(document.getElementById('products').getAttribute('price'));

        var totalCost = productCost+shippingCost;

        var total = document.getElementById('total');
        total.innerHTML = totalCost.toFixed(2);
    }
    updateTotalCosts();
</script>
</html>
