<?php
    if(isset($_POST['user']) && isset($_POST['pass'])){
        $user = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['pass'],FILTER_SANITIZE_STRING);

        include('conn.php');

        $query = "SELECT * FROM `Users` WHERE `Name_usr`='$user' AND `Pass_usr`='$pass';";
        $result = mysqli_query($conn,$query);
        $row = mysqli_num_rows($result);

        if($row==1){
            $info = mysqli_fetch_array($result);

            print("Success!");
            session_start();
            $_SESSION['user'] = $info['Name_usr'];
            $_SESSION['role'] = $info['Role_usr'];

            header("location: /Praktyki/admin/dashboard");
        }
        else{
            print("Error!");
            $_SESSION['error'] = "user doesn't exist";

            header("location: /Praktyki/admin");
        }
    }
