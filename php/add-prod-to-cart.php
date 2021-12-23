<?php
    if(isset($_POST['Submit']) && isset($_POST['Id_cfe'])){
        session_start();
        include("conn.php");
        $Id_cfe = filter_var($_POST['Id_cfe'],FILTER_SANITIZE_NUMBER_INT);
        $Id_usr = $_SESSION['user_id'];

        $query = "SELECT * FROM `Cart` WHERE `Id_user`=$Id_usr AND `Id_product`=$Id_cfe;";
        $result = mysqli_query($conn,$query);
        $row = mysqli_num_rows($result);
        $info = mysqli_fetch_array($result);

        if($row == 0){
            $query = "INSERT INTO `Cart`(`Id_user`, `Quantity`, `Id_product`) VALUES ($Id_usr,1,$Id_cfe)";
            if($result = mysqli_query($conn,$query))
                header("location: /Praktyki");
            else
                print("Error while adding: " . mysqli_error($conn) . "<br>" . $query);
        }
        else{
            $Id_cart = $info['Id_cart'];
            $Quantity = $info['Quantity'];

            $query = "UPDATE `Cart` SET `Quantity`=$Quantity+1 WHERE `Id_cart`=$Id_cart;";
            if($result = mysqli_query($conn,$query))
                header("location: /Praktyki");
            else
                print("Error while adding: " . mysqli_error($conn) . "<br>" . $query);
        }
    }
?>