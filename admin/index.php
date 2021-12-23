<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" href="/Praktyki/img/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/Praktyki/css/style.css" rel="stylesheet">
    <link href="/Praktyki/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['user'])) {
        print('<section class="py-5 text-center container">
                <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                        <h1 class="fw-light">Login</h1>
                        <form method="POST" action="../php/admin-login.php" class="login lead text-muted">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="floatingInput" placeholder="Username" name="user" autocomplete="username">
                                <label for="user" class="floatingInput">User</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="pass" autocomplete="currnet-password">
                                <label for="pass" class="floatingInput">Password</label>
                            </div>
                            <button type="submit" class="w-100 btn btn-lg btn-primary">Login</button>
                        </form>
                    </div>
                </div>');
    }elseif($_SESSION['role']==0){
        header("location: dashboard");
    }
    else{
        header("location: /Praktyki");
    }
    ?>
</body>

</html>