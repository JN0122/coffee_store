<?php
    include('php/conn.php');

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
    
        if(isset($_POST['order'])){
            $idShipping = filter_var($_POST['Id_shipping'],FILTER_SANITIZE_NUMBER_INT);
            $idAddress = filter_var($_POST['Id_address'],FILTER_SANITIZE_NUMBER_INT);
            $idUser = $_SESSION['user_id'];

            //Changing default shipping address for user
            $query = "UPDATE `Users` SET `Id_address`='$idAddress' WHERE `Id_usr`='$idUser';";
            if(!mysqli_query($conn, $query))
                echo mysqli_error($conn);

            //Getting informations about products in cart
            $total = 0;
            $productId = Array();
            $productQuantity = Array();
            $query = "SELECT * FROM `Cart`,`Coffee` WHERE `Id_user`=$idUser AND `Cart`.`Id_product` = `Coffee`.`Id_cfe`;";
            $result = mysqli_query($conn, $query);

            while($itemInCart = mysqli_fetch_array($result)){
                $productId[] = $itemInCart['Id_product'];
                $productQuantity[] = $itemInCart['Quantity'];
                $total += $itemInCart['Price_cfe'] * $itemInCart['Quantity'];
            }

            // Getting informations about shipping method
            $query = "SELECT * FROM `Shipping` WHERE `Id_shipping` = '$idShipping';";
            $result = mysqli_query($conn, $query);
            $shippingInfo = mysqli_fetch_array($result);
            $total += $shippingInfo['Price'];

            $query = "INSERT INTO `Orders`(`Id_user`, `Id_shipping`, `Total_amount`) VALUES ('$idUser','$idShipping','$total')";
            if(mysqli_query($conn, $query)){
                $ordersId = mysqli_insert_id($conn);

                for($i=0; $i < count($productId); $i++){
                    $query = 'INSERT INTO `Orders_products`(`Id_order`, `Id_product`, `Quantity`) VALUES ("'.$ordersId.'","'.$productId[$i].'","'.$productQuantity[$i].'")';
                    if(!mysqli_query($conn, $query))
                        echo mysqli_error($conn);
                }
                $query = "DELETE FROM `Cart` WHERE `Id_user`='$idUser'";
                if(!mysqli_query($conn, $query))
                    echo mysqli_error($conn);

            }
            else
                echo mysqli_error($conn);
            
        } else
            header('location: /Praktyki');

    ?>

    <div class="container-fluid">
        <div class="row m-5 text-center">
            <h1>Thank you for your order!</h1>
            <img src="img/ok.png" style="height:200px; width:auto;" class="m-auto">
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
<script src="js/bootstrap.bundle.min.js"></script>
</html>