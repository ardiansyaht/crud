<!-- File: app/Views/login.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../loginv1/images/icons/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../loginv1/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/vendor/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/vendor/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/css/util.css">
    <link rel="stylesheet" type="text/css" href="../loginv1/css/main.css">
</head>

<body>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('../loginv1/images/bg-01.jpg');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="post" action="<?= site_url('login/authenticate'); ?>">
                    <?php if (!empty($err)) : ?>
                        <div class="alert alert-danger" role="alert"><?= $err; ?></div>
                    <?php endif; ?>
                    <span class="login100-form-logo">
                        <i class="zmdi zmdi-landscape"></i>
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27">
                        Log in
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="text" name="username" placeholder="Username" value="<?= $username; ?>">
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="password" placeholder="Password">
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn" type="submit" name="login">
                            Login
                        </button>
                    </div>

                    <div class="text-center p-t-30">
                        <a class="txt1" href="<?= site_url('register'); ?>">
                            Sign Up?
                        </a>

                        <div class="text-center p-t-5">
                            <a class="txt1" href="<?= site_url('forgot_password'); ?>">
                                Forgot Password?
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="dropDownSelect1"></div>
    <script src="../loginv1/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../loginv1/vendor/animsition/js/animsition.min.js"></script>
    <script src="../loginv1/vendor/bootstrap/js/popper.js"></script>
    <script src="../loginv1/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../loginv1/vendor/select2/select2.min.js"></script>
    <script src="../loginv1/vendor/daterangepicker/moment.min.js"></script>
    <script src="../loginv1/vendor/daterangepicker/daterangepicker.js"></script>
    <script src="../loginv1/vendor/countdowntime/countdowntime.js"></script>
    <script src="../loginv1/js/main.js"></script>

</body>

</html>