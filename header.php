<header class="p-3 bg-dark text-white">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/Praktyki" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <img src="img/coffee-bean.png" class="bi me-2" width="50" height="42" role="img" aria-label="CoffeeBean" />
            </a>

            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                <li><a href="/Praktyki" class="nav-link px-2 text-white">Products</a></li>
            </ul>

            <!-- <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
          <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
        </form> -->
            <?php
            session_start();
            if (isset($_SESSION['user'])) {
                include('php/conn.php');
                $idUser = $_SESSION['user_id'];

                $query = "SELECT * FROM `Cart` WHERE `Id_user`=$idUser;";
                $result = mysqli_query($conn,$query);
                if($result)
                    $itemsInCart = mysqli_num_rows($result);
                else
                    $itemsInCart = 0;
                print('<div class="text-end">
                            <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                <li>
                                    <a href="cart.php">
                                        <button type="button" class="btn btn-outline-light me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                                            </svg>
                                            '.$itemsInCart.'
                                        </button>
                                    </a>
                                </li>
                                <li>
                                <div class="dropdown text-end">
                                    <a href="#" class="d-block link-light text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="img/user-img.png" width="32" height="32" class="rounded-circle">
                                    </a>
                                    <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" id="dropdown">
                                        <li><span class="dropdown-item">Hello ' . $_SESSION['user'] . '!</span></li>
                                        <li><a class="dropdown-item"href="edit-profile.php">Edit profile</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="php/logout.php">Sign out</a></li>
                                    </ul>
                                </div>
                                </li>
                            </ul>
                    </div>');
            }
            ?>
        </div>
    </div>
</header>