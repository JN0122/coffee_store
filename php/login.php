<?php
    if(isset($_POST['user']) && isset($_POST['pass'])){
        $user = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
        $pass = filter_var($_POST['pass'],FILTER_SANITIZE_STRING);

        include('conn.php');

        $query = "SELECT * FROM `Users` WHERE `Name_usr`='$user';";
        $result = mysqli_query($conn,$query);
        $row = mysqli_num_rows($result);

        if($row==1){
            $info = mysqli_fetch_array($result);

            if(password_verify($pass, $info['Pass_usr'])){
                session_start();
                $_SESSION['user'] = $info['Name_usr'];
                $_SESSION['role'] = $info['Role_usr'];
                $_SESSION['user_id'] = $info['Id_usr'];
            }
            else{
                echo "Wrong password!";
            }
        }
        else{
            print("Error!");
            $_SESSION['error'] = "user doesn't exist";
        }
        header("location: ../");
    }
?>
