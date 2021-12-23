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

function duplicatesNotFound($name)
{
    include('/Applications/XAMPP/xamppfiles/htdocs/Praktyki/php/conn.php');
    $query = "SELECT * FROM `Coffee_category` WHERE `Category_name`='$name';";
    $result = mysqli_query($conn, $query);
    $row = mysqli_num_rows($result);

    if ($row == 0)
        return 1;
    else
        return 0;
}

if (isset($_POST['s'])) {
    if ($_POST['s'] == 'Add category') {
        $name_sanit = filter_var($_POST['name_cat'], FILTER_SANITIZE_STRING);

        if (duplicatesNotFound($name_sanit)) {
            $query = "INSERT INTO `Coffee_category` VALUES (NULL,'$name_sanit');";
            if(mysqli_query($conn, $query))
                header('location: categories.php');
            else
                print("Error while adding: " . mysqli_error($conn));
        } else {
            $duplicate = 1;
        }
    } elseif ($_POST['s'] == 'Remove category') {
        $id_sanit = filter_var($_POST['id_cat'], FILTER_SANITIZE_NUMBER_INT);
        $query = "DELETE FROM `Coffee_category` WHERE `Id_cat`=$id_sanit;";

        if(mysqli_query($conn, $query))
            header('location: categories.php');
        else
            print("Error while removing: " . mysqli_error($conn));
    } elseif ($_POST['s'] == 'Update category') {
        $id_sanit = filter_var($_POST['id_cat'], FILTER_SANITIZE_NUMBER_INT);
        $name_sanit = filter_var($_POST['name_cat'], FILTER_SANITIZE_STRING);

        if (duplicatesNotFound($name_sanit)) {
            $query = "UPDATE `Coffee_category` SET `Category_name`='$name_sanit' WHERE `Id_cat`=$id_sanit;";
            if(mysqli_query($conn, $query))
                header('location: categories.php');
            else
                print("Error while updating: " . mysqli_error($conn));
        } else {
            $duplicate = 1;
        }
    }
} else {
    $duplicate = 0;
}
?>

<body>
    <div class="row">
        <?php include('nav.php'); ?>
        <main role="main" class="col-10 pt-3 px-4">
            <table class="table align-middle table-striped">
                <thead>
                    <tr>
                        <td>Category name</td>
                        <td>Products in category</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $query = "SELECT * FROM `Coffee_category`;";
                    $result = mysqli_query($conn, $query);

                    while ($category = mysqli_fetch_array($result)) {
                        print('<form method="POST"><tr>
                        <td><input type=text class="form-control" name="name_cat" value="' . $category['Category_name'] . '"></td>');

                        $query = "SELECT * FROM `Coffee_category`,`Coffee` WHERE `Coffee_category`.`Id_cat` = `Coffee`.`Id_cat` AND `Coffee`.`Id_cat`=" . $category['Id_cat'] . ";";
                        $r = mysqli_query($conn, $query);
                        $info = mysqli_num_rows($r);
                        print("<td>$info</td>");

                        print('<td><input type="hidden" name="id_cat" value=' . $category['Id_cat'] . '>
                            <input class="btn btn-secondary m-1" type="submit" value="Update category" name="s">');
                        if ($info == 0) {
                            print('<input class="btn btn-danger m-1" type="submit" onclick="return confirm(\'Are you sure?\')" value="Remove category" name="s">');
                        }
                        print('</td></tr></form>');
                    }
                    ?>

                </tbody>
            </table>

            <form method="POST">
                <div class="row">
                    <div class="col-4">
                        <input type="text" class="form-control" name="name_cat" placeholder="Category name" required>
                    </div>
                    <div class="col-2">
                        <input type="submit" class="btn btn-success" value="Add category" name="s">
                    </div>
                </div>
                
            </form>
            <?php
                if ($duplicate) {
                    print('<div class="alert alert-danger mt-3" role="alert">
                    Error, duplicate found!
                  </div>');
                    $duplicate = 0;
                }
                ?>
        </main>
    </div>
</body>

</html>