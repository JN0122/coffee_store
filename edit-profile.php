<?php
include('header.php');
include('php/redirect.php');

$userId = $_SESSION['user_id'];
$query = "SELECT * FROM `Users_address` WHERE `Id_user`='$userId'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_array($result);

if (isset($_POST['inputCountry']) && isset($_POST['inputCity']) && isset($_POST['inputZip']) && isset($_POST['inputStreet'])) {
    // Array ( [inputCountry] => Poland [inputCity] => Rybnik [inputZip] => 44200 [inputStreet] => Wodzisławska )
    $inputCountry = filter_var($_POST['inputCountry'], FILTER_SANITIZE_STRING);
    $inputCity = filter_var($_POST['inputCity'], FILTER_SANITIZE_STRING);
    $inputZip = str_replace('-', '', filter_var($_POST['inputZip'], FILTER_SANITIZE_NUMBER_INT));
    $inputStreet = filter_var($_POST['inputStreet'], FILTER_SANITIZE_STRING);
    $idUser = $_SESSION['user_id'];

    if ($user) {
        $query = "UPDATE `Users_address` SET `Country_adr`='$inputCountry',`City_adr`='$inputCity',`Zip_code_adr`='$inputZip',`Street_adr`='$inputStreet' WHERE `Id_user`='$idUser';";

        if (!mysqli_query($conn, $query))
            echo mysqli_error($conn);
    } else {
        $query = "INSERT INTO `Users_address`(`Id_user`, `Country_adr`, `City_adr`, `Zip_code_adr`, `Street_adr`) VALUES ('$idUser','$inputCountry','$inputCity','$inputZip','$inputStreet');";

        if (!mysqli_query($conn, $query))
            echo mysqli_error($conn);

        $idAddress = mysqli_insert_id($conn);
        $query = "UPDATE `Users` SET `Id_address` = '$idAddress' WHERE `Id_usr`='$idUser';";

        if (!mysqli_query($conn, $query))
            echo mysqli_error($conn);
    }
    header("location: edit-profile.php");
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>Coffee store - edit profile</title>
</head>


<body>
    <div class="container p-4">
        <form method="POST">
            <div class="col-md-6 form-floating my-2">
                <input type="text" class="form-control" id="inputCountry" name="inputCountry" placeholder="e.g. Poland" value="<?php echo $user['Country_adr']; ?>" required>
                <label for="inputCountry" class="form-label">Country</label>
            </div>
            <div class="col-md-6 form-floating my-2">
                <input type="text" class="form-control" id="inputCity" name="inputCity" placeholder="e.g. Rybnik" value="<?php echo $user['City_adr']; ?>" required>
                <label for="inputCity" class="form-label">City</label>
            </div>
            <div class="col-md-2 form-floating my-2">
                <input type="text" class="form-control" name="inputZip" id="inputZip" placeholder="e.g. 44-200" value="<?php echo substr($user['Zip_code_adr'], 0, 2) . '-' . substr($user['Zip_code_adr'], 2, 5); ?>" required>
                <label for="inputZip" class="form-label">Zip code</label>
            </div>
            <div class="col-md-8 form-floating my-2">
                <input type="text" class="form-control" id="inputStreet" name="inputStreet" placeholder="e.g. Wodzisławska" value="<?php echo $user['Street_adr']; ?>" required>
                <label for="inputStreet" class="form-label">Street</label>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <?php include('footer.php'); ?>
</body>
<script src="js/bootstrap.bundle.min.js"></script>

</html>