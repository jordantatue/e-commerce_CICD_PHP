<?php
session_start();
?>
<!--
   Si utilisateur/trice est non identifié(e), on affiche le formulaire
-->

<!-- inclusion des variables et fonctions -->


<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de recettes - Page d'accueil</title>
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="/login/static/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</head>

<body>
    <section class="container main_sec">
        <div class="main">
            <div class="navbar row">
                <?php
                require_once(__DIR__ . '/../base/header.php');
                ?>
            </div>
            <br>
            <div class="content row">
                <div class="col-8">
                    <h1>Web Design & <br><span>Development</span> <br>Course</h1>
                    <p class="par">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt neque
                        expedita atque eveniet <br> quis nesciunt. Quos nulla vero consequuntur, fugit nemo ad delectus
                        <br> a quae totam ipsa illum minus laudantium?
                    </p>

                    <button class="cn"><a href="#">JOIN US</a></button>
                </div>

                <div class="col form-4">
                    <?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
                        <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                            <div class="card-body">
                                <form action="submit_login.php" method="post">
                                    <?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo $_SESSION['LOGIN_ERROR_MESSAGE'];
                                            unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <h2>Login Here</h2>
                                    <br>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email Here">
                                    <p></p>
                                    <input type="password" name="password" class="form-control" placeholder="Enter Password Here">
                                    <button type="submit" class="btnn">Login</button>

                                    <p class="link">Don't have an account<br>
                                        <a href="#">Sign up </a> here</a>
                                    </p>
                                    <p class="liw">Log in with</p>

                                    <div class="icons">
                                        <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                                        <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                                        <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                                        <a href="#"><ion-icon name="logo-google"></ion-icon></a>
                                        <a href="#"><ion-icon name="logo-skype"></ion-icon></a>
                                    </div>

                                </form>
                            </div>
                        </div>




                    <?php else : ?>
                        <div class="alert alert-success" role="alert">
                            Bonjour <?php echo $_SESSION['LOGGED_USER']['email']; ?> et bienvenue sur le site !
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    require_once(__DIR__ . '/../base/footer.php');
    ?>
    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>

</html>
