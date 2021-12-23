<!doctype html>
<html lang="en">

<head>
    <link rel="icon" href="/Praktyki/img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/Praktyki/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin - dashboard</title>
</head>
<?php
include('php/redirect.php');
include('/Applications/XAMPP/xamppfiles/htdocs/Praktyki/php/conn.php');
include('header.php');

function duplicatesNotFound($name, $id = 0)
{
    include('/Applications/XAMPP/xamppfiles/htdocs/Praktyki/php/conn.php');
    $query = "SELECT * FROM `Coffee` WHERE `Name_cfe`='$name' AND `Id_cfe`<>$id;";
    $result = mysqli_query($conn, $query);
    $row = mysqli_num_rows($result);

    if ($row == 0)
        return 1;
    else
        return 0;
}

// Upload files 
$upload_dir = "/Applications/XAMPP/xamppfiles/htdocs/Praktyki/img/products/";
if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
    global $real_name;
    $allowedExtensions = ['png', 'jpg'];
    $tmp_file = $_FILES['photo']['tmp_name'];
    $real_name = filter_var($_FILES['photo']['name'], FILTER_SANITIZE_URL);
    $nameExplode = explode('.', $real_name);

    if (in_array($nameExplode[1], $allowedExtensions, true)) {
        $query = "SELECT `Src_cfe` FROM `Coffee` WHERE `Src_cfe` = '$real_name';";
        $result = mysqli_query($conn, $query);
        $row = mysqli_num_rows($result);

        if ($row == 0) {
            if (!move_uploaded_file($tmp_file, $upload_dir . $real_name))
                echo "Error while uploading photo!";
        } else {
            print("Photo exists changing name");
            $real_name = $nameExplode[0] . "_" . time() . "." . $nameExplode[1];
            if (!move_uploaded_file($tmp_file, $upload_dir . $real_name))
                echo "Error while uploading photo with timestamp!";
        }
    } else
        echo "Not proper extension!";
}


// POST
if (isset($_POST["submit"])) {
    if ($_POST["submit"] == 'Add product') {
        $company_sanit = filter_var($_POST['Comp_id'], FILTER_SANITIZE_NUMBER_INT);
        $name_sanit = filter_var($_POST['Name_cfe'], FILTER_SANITIZE_STRING);
        $category_sanit = filter_var($_POST['Cat_id'], FILTER_SANITIZE_NUMBER_INT);
        $weight_sanit = filter_var($_POST['Weight_cfe'], FILTER_SANITIZE_NUMBER_INT);
        $price_sanit = filter_var($_POST['Price_cfe'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $ingreients_sanit = filter_var($_POST['Ingredients_cfe'], FILTER_SANITIZE_STRING);
        $src_sanit = filter_var($real_name, FILTER_SANITIZE_STRING);

        if (duplicatesNotFound($name_sanit)) {
            $query = "INSERT INTO `Coffee` VALUES (NULL,'$name_sanit',$category_sanit,$company_sanit,$weight_sanit,$price_sanit,'$ingreients_sanit','$src_sanit');";

            if ($result = mysqli_query($conn, $query))
                header('location: products.php');
            else
                print("error while adding product: " . mysqli_error($conn));
        } else
            $duplicate = 1;
    } elseif ($_POST["submit"] == 'Remove product') {
        $name_sanit = filter_var($_POST['Name_cfe'], FILTER_SANITIZE_STRING);
        $query = "DELETE FROM `Coffee` WHERE `Name_cfe`='$name_sanit';";

        // Get file name and delete it
        $querySrc = "SELECT `Src_cfe` FROM `Coffee` WHERE `Name_cfe`='$name_sanit';";
        $resultSrc = mysqli_query($conn, $querySrc);
        $Src = mysqli_fetch_array($resultSrc);
        if (!unlink($upload_dir . $Src['Src_cfe']))
            echo "Error while deleting photo!";

        if (mysqli_query($conn, $query))
            header('location: products.php');
        else
            print("Error while removing product: " . mysqli_error($conn));
    } elseif ($_POST["submit"] == 'Update product') {
        $id_sanit = filter_var($_POST['Id_cfe'], FILTER_SANITIZE_NUMBER_INT);
        $company_sanit = filter_var($_POST['Comp_id'], FILTER_SANITIZE_NUMBER_INT);
        $name_sanit = filter_var($_POST['Name_cfe'], FILTER_SANITIZE_STRING);
        $category_sanit = filter_var($_POST['Cat_id'], FILTER_SANITIZE_NUMBER_INT);
        $weight_sanit = filter_var($_POST['Weight_cfe'], FILTER_SANITIZE_NUMBER_INT);
        $price_sanit = filter_var($_POST['Price_cfe'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $ingreients_sanit = filter_var($_POST['Ingredients_cfe'], FILTER_SANITIZE_STRING);

        if (duplicatesNotFound($name_sanit, $id_sanit)) {
            $query = "UPDATE `Coffee` SET `Name_cfe`='$name_sanit',`Id_cat`=$category_sanit,`Id_cmp`=$company_sanit,`Weight_cfe`=$weight_sanit,`Price_cfe`=$price_sanit,`Ingredients_cfe`='$ingreients_sanit' WHERE `Id_cfe`=$id_sanit;";
            if (mysqli_query($conn, $query))
                header('location: products.php');
            else
                print("Error while updating product: " . mysqli_error($conn));
        } else
            $duplicate = 1;
    }
} else
    $duplicate = 0;

?>

<body>
    <div class="row">
        <?php include('nav.php'); ?>
        <main role="main" class="col-10 pt-3 px-4">
            <?php
            // If duplicate display error alert
            if (isset($duplicate) && $duplicate) {
                print('<div class="alert alert-danger mt-3" role="alert">
                        Error, duplicate found!
                    </div>');
                $duplicate = 0;
            }
            ?>
            <table class="table align-middle table-sm table-striped">
                <thead>
                    <tr>
                        <td>Image</td>
                        <td>Company</td>
                        <td>Name</td>
                        <td>Category</td>
                        <td>Weight (g)</td>
                        <td>Price (PLN)</td>
                        <td>Ingredients</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    $query = "SELECT * FROM `Coffee`,`Coffee_category`,`Coffee_company` WHERE `Coffee`.`Id_cat` = `Coffee_category`.`Id_cat` AND `Coffee_company`.`Id_cmp` = `Coffee`.`Id_cmp`;";
                    $result = mysqli_query($conn, $query);

                    while ($product_info = mysqli_fetch_array($result)) {
                        // Categories
                        $queryCategory = "SELECT * FROM `Coffee_category` WHERE `Coffee_category`.`Id_cat` <> " . $product_info['Id_cat'] . ";";
                        $categories_result = mysqli_query($conn, $queryCategory);

                        // Companies
                        $query = "SELECT * FROM `Coffee_company` WHERE `Coffee_company`.`Id_cmp` <> " . $product_info['Id_cmp'] . ";";
                        $companies_result = mysqli_query($conn, $query);

                        print('<form method="POST" enctype="multipart/form-data"><tr>
                        <input type="hidden" name="Id_cfe" value=' . $product_info['Id_cfe'] . '>
                        <td><img style="width:70px; height: 70px;" src="../../img/products/' . $product_info['Src_cfe'] . '"></td>
                        <td><select class="form-control" name="Comp_id"><option value=' . $product_info['Id_cmp'] . ' default>' . $product_info['Company_name'] . "</option>");
                        while ($companies = mysqli_fetch_array($companies_result)) {
                            print('<option value=' . $companies['Id_cmp'] . '>' . $companies['Company_name'] . '</option>');
                        }
                        print('</select></td>
                        <td><input type=text class="form-control" name="Name_cfe" value="' . $product_info['Name_cfe'] . '"></td>
                        <td><select class="form-control" name="Cat_id"><option value=' . $product_info['Id_cat'] . " default>" . $product_info['Category_name'] . "</option>");
                        while ($categories = mysqli_fetch_array($categories_result)) {
                            echo '<option value=' . $categories['Id_cat'] . '>' . $categories['Category_name'] . '</option>';
                        }
                        print('</select></td>
                        <td><input type=text class="form-control" style="width: 80px;" name="Weight_cfe" value="' . $product_info['Weight_cfe'] . '"></td>
                        <td><input type=text class="form-control" style="width: 90px;" name="Price_cfe" value="' . $product_info['Price_cfe'] . '"></td>
                        <td><input type=text class="form-control" name="Ingredients_cfe" value="' . $product_info['Ingredients_cfe'] . '"></td>
                        <td style="width: 150px;"><input class="btn btn-secondary m-1" type="submit" value="Update product" name="submit">
                        <input class="btn btn-danger m-1" type="submit" onclick="return confirm(\'Are you sure?\')" value="Remove product" name="submit"></td>
                        </tr></form>');
                    }
                    ?>

                </tbody>
            </table>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-3">
                        <?php
                        $query = "SELECT * FROM `Coffee_company`;";
                        $companies_result = mysqli_query($conn, $query);

                        echo '<select class="form-control" name="Comp_id">';
                        while ($companies = mysqli_fetch_array($companies_result)) {
                            print('<option value=' . $companies['Id_cmp'] . '>' . $companies['Company_name'] . '</option>');
                        }
                        echo '</select>';
                        ?>
                    </div>
                    <div class="col-4">
                        <input type=text class="form-control" name="Name_cfe" placeholder="Name" required>
                    </div>
                    <div class="col-3">
                        <?php
                        $queryCategory = "SELECT * FROM `Coffee_category`;";
                        $categories_result = mysqli_query($conn, $queryCategory);

                        echo '<select class="form-control" name="Cat_id" required>';
                        while ($categories = mysqli_fetch_array($categories_result)) {
                            echo '<option value=' . $categories['Id_cat'] . '>' . $categories['Category_name'] . '</option>';
                        }
                        echo '</select>';
                        ?>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-2">
                        <input type=number class="form-control" name="Weight_cfe" min=0 placeholder="Weight (g)" required>
                    </div>
                    <div class="col-4">
                        <input type=text class="form-control" name="Price_cfe" placeholder="Price" required>
                    </div>
                    <div class="col-4">
                        <input type=text class="form-control" name="Ingredients_cfe" placeholder="Ingredients" required>
                    </div>
                </div>
                <div class="row mt-3 mb-5">
                    <div class="col-3">
                        <input type="file" class="form-control" name="photo" accept=".png, .jpg">
                    </div>
                    <div class="col-2">
                        <input type="submit" class="btn btn-success" value="Add product" name="submit">
                    </div>
                </div>

            </form>
        </main>
    </div>
</body>

</html>