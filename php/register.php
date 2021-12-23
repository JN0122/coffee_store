<?php
    if(isset($_POST['user']) && isset($_POST['pass'])){
        $user = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
        $pass = password_hash(filter_var($_POST['pass'],FILTER_SANITIZE_STRING), PASSWORD_BCRYPT);

        include('conn.php');

        $query = "INSERT INTO `Users` (`Name_usr`, `Pass_usr`) VALUES ('$user', '$pass')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            print("New record created successfully");

            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['role'] = 1;
            $_SESSION['user_id'] = mysqli_insert_id($conn);
          } 
          else{
            print("error while creating new user: ".mysqli_error($conn));
            $_SESSION['error'] = "error while creating new user: ".mysqli_error($conn);
          }
        header("location: ../");
    }
?>
