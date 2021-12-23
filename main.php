<div class="container main">
    <div class="row">
        <!-- <aside class="col-1">
        <?php
                // include("php/conn.php");

                // $query = "SELECT `Category_name` FROM `Coffee_category`;";
                // $result = mysqli_query($conn, $query);

                // while ($category = mysqli_fetch_array($result)) {
                //     print('<div><a href="#">'.$category['Category_name'].'</a></div>');
                // }
                ?>
        </aside> -->
        <main class="col">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                $query = "SELECT * FROM `Coffee`,`Coffee_company` WHERE `Coffee`.`Id_cmp`=`Coffee_company`.`Id_cmp`";
                $result = mysqli_query($conn, $query);

                while ($product = mysqli_fetch_array($result)) {
                    $name = $product["Company_name"] . " " . $product["Name_cfe"] . " " . $product["Weight_cfe"] . " g";
                    print('<div class="col mb-5">
                            <div class="card h-100">
                                <img class="card-img-top" src="img/products/' . $product["Src_cfe"] . '" alt="...">
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <h5 class="fw-bolder">' . $name . '</h5>
                                        ' . $product["Price_cfe"] . ' PLN
                                    </div>
                                </div>
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center">
                                        <form method="POST" action="php/add-prod-to-cart.php">
                                            <input type="hidden" name="Id_cfe" value="'.$product["Id_cfe"].'">
                                            <input type="submit" name="Submit" class="btn btn-outline-dark mt-auto" value="Add to cart"/>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>');
                }
                ?>
            </div>
        </main>
    </div>
</div>