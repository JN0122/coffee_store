<?php
include('php/conn.php');

if (!isset($_POST['checkout']))
    header('location: /Praktyki');

function twoDigitsAfterDot($totalPrice)
{
    $totalExplode = explode('.', $totalPrice);

    if (isset($totalExplode[1])) {
        if (strlen($totalExplode[1]) == 1)
            return $totalPrice = $totalPrice . "0";
    } else
        return $totalPrice = $totalPrice . ".00";
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
    <title>Coffee store - checkout</title>
</head>


<body>
    <?php include('header.php'); ?>

    <div class="container-fluid">
        <div class="row m-5">
            <div class="row mb-2">
                <div class="col-12">
                    <h4><b>Ordered items</b></h4>
                </div>
            </div>
            <?php
            if (isset($_SESSION['user'])) {
                $total = 0;
                $idUser = $_SESSION['user_id'];

                $query = "SELECT `Id_address` FROM `Users` WHERE `Id_usr` = '$idUser';";
                $result = mysqli_query($conn, $query);
                $defaultAddress = mysqli_fetch_array($result);
                $addrId = $defaultAddress['Id_address'];

                if ($addrId == 0)
                    header("location: edit-profile.php");

                $query = "SELECT * FROM `Cart`,`Coffee`,`Coffee_category`,`Coffee_company` WHERE `Id_user`=$idUser AND `Cart`.`Id_product` = `Coffee`.`Id_cfe` AND `Coffee`.`Id_cat` = `Coffee_category`.`Id_cat` AND `Coffee`.`Id_cmp` = `Coffee_company`.`Id_cmp`;";
                $result = mysqli_query($conn, $query);
                $totalItems = mysqli_num_rows($result);

                while ($itemInCart = mysqli_fetch_array($result)) {
                    print('<div class="row">
                        <form class="row main align-items-center" method="POST">
                                <input type="hidden" name="cartId" value="' . $itemInCart['Id_cart'] . '">
                                <div class="col-1"><img class="img-fluid" src="img/products/' . $itemInCart['Src_cfe'] . '"></div>
                                <div class="col-5">
                                    <div class="row text-muted">' . $itemInCart['Category_name'] . '</div>
                                    <div class="row">' . $itemInCart['Company_name'] . ' ' . $itemInCart['Name_cfe'] . '</div>
                                </div>
                                <div class="col text-muted text-center">
                                    <span class="m-3">' . $itemInCart['Quantity'] . '</span>
                                </div>
                                <div class="col text-center"> ' . $itemInCart['Price_cfe'] . ' PLN</div>
                        </form>
                    </div>');
                    $total += $itemInCart['Price_cfe'];
                }
            }

            ?>
            <div class="row mt-5 mb-2">
                <div class="col-12">
                    <h4><b>Shipping method</b></h4>
                </div>
            </div>
            <?php
            $shippingId = filter_var($_POST['shippingMethod'], FILTER_SANITIZE_NUMBER_INT);
            $userId = $_SESSION['user_id'];

            $query = "SELECT * FROM `Shipping` WHERE `Id_shipping`='$shippingId';";
            $result = mysqli_query($conn, $query);
            $shippingMethod = mysqli_fetch_array($result);

            print('<div class="row mx-3">
                            <div class="col-8">
                                <h6 class="">' . $shippingMethod['Courier_name'] . '</h6>
                            </div>
                            <div class="col text-center">
                                ' . $shippingMethod['Price'] . ' PLN
                            </div>
                        </div>');
            $total += $shippingMethod['Price'];
            ?>
            <div class="row mt-5 mb-2">
                <div class="col-12">
                    <h4><b>Choose your shipping adress</b></h4>
                </div>
            </div>
            <form method="POST" class="row" action="ok.php">
                <?php
                print('<input type="hidden" name="Id_shipping" value="' . $shippingId . '">');

                $query = "SELECT * FROM `Users_address` WHERE `Id_address` = '$addrId';";
                $result = mysqli_query($conn, $query);
                $shippingAddress = mysqli_fetch_array($result);

                print('<div class="col-4 my-2 text-center">
                                <input type="radio" class="btn-check" name="Id_address" id="success-outlined-' . $shippingAddress['Id_address'] . '" autocomplete="off" value="' . $shippingAddress['Id_address'] . '" checked>
                                <label class="btn btn-outline-secondary p-3" for="success-outlined-' . $shippingAddress['Id_address'] . '">
                                <div class="row">
                                    <h4>' . $shippingAddress['Country_adr'] . '</h4>
                                    <h5>' . $shippingAddress['City_adr'] . ', ' . substr($shippingAddress['Zip_code_adr'], 0, 2) . '-' . substr($shippingAddress['Zip_code_adr'], 2, 5) . '</h5>
                                    <h5>' . $shippingAddress['Street_adr'] . '</h5>
                                </label>
                                </div>
                            </div>');

                $query = "SELECT * FROM `Users_address` WHERE `Id_user` = '$userId' AND `Id_address` <> '$addrId';";


                $result = mysqli_query($conn, $query);
                while ($shippingAddress = mysqli_fetch_array($result)) {
                    print('<div class="col-4 my-2 text-center">
                                    <input type="radio" class="btn-check" name="Id_address" id="success-outlined-' . $shippingAddress['Id_address'] . '" autocomplete="off" value="' . $shippingAddress['Id_address'] . '">
                                    <label class="btn btn-outline-secondary p-3" for="success-outlined-' . $shippingAddress['Id_address'] . '">
                                    <div class="row">
                                        <h4>' . $shippingAddress['Country_adr'] . '</h4>
                                        <h5>' . $shippingAddress['City_adr'] . ', ' . substr($shippingAddress['Zip_code_adr'], 0, 2) . '-' . substr($shippingAddress['Zip_code_adr'], 2, 5) . '</h5>
                                        <h5>' . $shippingAddress['Street_adr'] . '</h5>
                                    </label>
                                    </div>
                                </div>');
                }
                ?>
                <div class="row mt-5 mb-2">
                    <div class="col-12">
                        <h4><b>Total</b></h4>
                    </div>
                </div>
                <div class="row mx-3">
                    <div class="col-8">
                        <h6>Total costs with shippment</h6>
                    </div>
                    <div class="col text-center">
                        <h5><?php echo $total; ?> PLN</h5>
                    </div>
                </div>
                <input type="submit" name="order" value="Order" class="btn btn-success mx-2 my-5 p-3 w-100">
            </form>
            <div class="border-top p-4"><a href="/Praktyki/cart.php" class="backToShop"><span class="text-muted">&leftarrow; Back to the cart</span></a></div>
        </div>
    </div>

    <?php include('footer.php'); ?>
</body>
<script src="js/bootstrap.bundle.min.js"></script>

</html>