<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/log-in.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css?v=1">
</head>

<body>
    <div class="overlay"></div>

    <div class="login" style="background-image: url(<?= BASE_URL ?>assets/images/login-bg.jpg);">

        <!-- Error Message -->
        <?php if (!empty($data['errors']['login'])): ?>
            <p class="error-massage"><?= $data['errors']['login'] ?></p>
        <?php endif; ?>

        <div class="logo-title">
            <div class="title">
                <img src="<?= BASE_URL ?>assets/images/car.png" alt="">
                <h2>ParkKinetic</h2>
            </div>
            <p>smart parking command</p>
        </div>

        <div class="login-container">
            <div class="title">
                <h2>Welcome Back</h2>
                <p>Access your urban parking dashboard</p>
            </div>

            <form action="<?= BASE_URL ?>Auth/doLogin" method="post" id="login-form">

                <!-- Role -->
                <label class="role">Select Your Role</label>
                <div class="role-box">

                    <label class="role-card">
                        <input type="radio" name="role" value="driver" required>
                        <div class="card-content">
                            <i class="fa-solid fa-car"></i>
                            <h2>Driver</h2>
                        </div>
                    </label>

                    <label class="role-card">
                        <input type="radio" name="role" value="owner">
                        <div class="card-content">
                            <i class="fa-solid fa-car-tunnel"></i>
                            <h2>Space Owner</h2>
                        </div>
                    </label>

                    <label class="role-card">
                        <input type="radio" name="role" value="admin">
                        <div class="card-content">
                            <i class="fa-solid fa-user-tie"></i>
                            <h2>Admin</h2>
                        </div>
                    </label>

                </div>

                <!-- Email -->
                <div class="input-box">
                    <div class="box-1">
                        <input type="email" name="email" required value="<?= $data['old']['email'] ?? '' ?>">
                        <label>Email Address</label>
                    </div>

                    <!-- Password -->
                    <div class="box-2">
                        <input type="password" name="password" required>
                        <label>Password</label>
                    </div>
                </div>

                <!-- Validation -->
                <div class="validation">
                    <div class="remember">
                        <label class="container">
                            <input type="checkbox" name="remember">
                            <div class="checkmark"></div>
                        </label>
                        <label>Remember me</label>
                    </div>
                    <a href="#">Forgot password?</a>
                </div>

                <!-- Submit -->
                <input type="submit" class="login-btn" value="Login">

                <!-- Register -->
                <div class="account">
                    <h4>Don't have an account?</h4>
                    <a href="<?= BASE_URL ?>Auth/register">Create an account</a>
                </div>

            </form>
        </div>

        <div class="copyright">
            <p>© 2024 ParkKinetic Systems • Urban Mobility Solutions</p>
        </div>
    </div>

    <!-- JS -->
    <script src="<?= BASE_URL ?>assets/js/login.js"></script>
    <script src="<?= BASE_URL ?>assets/js/all.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>