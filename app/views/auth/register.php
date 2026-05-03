<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/all.min.css?v=1">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css?v=1">

    <title>Sign Up</title>
</head>

<body>
    <section class="sign-up">
        <div class="sign-up-container">

            <div class="header">
                <img src="<?= BASE_URL ?>assets/images/Overlay.png" class="header-image">
                <h2 class="header-text">Join the <span class="active-text">Flow</span></h2>
                <p>Optimize your urban mobility and space management with ParkKinetic.</p>
            </div>

            <div class="form">
                <form method="post" action="<?= BASE_URL ?>Auth/storeRegister">

                    <!-- Role -->
                    <label>Select Your Role</label>
                    <div class="role-box">

                        <label class="role-card">
                            <input type="radio" name="role" value="driver" required <?= (($data['old']['role'] ?? '') === 'driver') ? 'checked' : '' ?>>
                            <div class="btn-1">
                                <img src="<?= BASE_URL ?>assets/images/car-05-stroke-rounded.svg">
                                <div class="btn-content">
                                    <h2>I am a Driver</h2>
                                    <p>Find instant parking</p>
                                </div>
                            </div>
                        </label>

                        <label class="role-card">
                            <input type="radio" name="role" value="owner" required <?= (($data['old']['role'] ?? '') === 'owner') ? 'checked' : '' ?>>
                            <div class="btn-2">
                                <img src="<?= BASE_URL ?>assets/images/car-parking-02-stroke-rounded.svg">
                                <div class="btn-content">
                                    <h2>I am a Space Owner</h2>
                                    <p>Monetize your property</p>
                                </div>
                            </div>
                        </label>

                    </div>
                    <div class="text-danger small">
                        <?= $data['errors']['role'] ?? '' ?>
                    </div>

                    <!-- Inputs -->
                    <div class="input-box">

                        <div class="input-box-1">
                            <label>Full Name</label>
                            <input type="text" name="name" required value="<?= $data['old']['name'] ?? '' ?>">
                            <div class="text-danger small">
                                <?= $data['errors']['name'] ?? '' ?>
                            </div>

                            <label>Password</label>
                            <input type="password" name="password" required>
                            <div class="text-danger small">
                                <?= $data['errors']['password'] ?? '' ?>
                            </div>
                        </div>

                        <div class="input-box-2">
                            <label>Email Address</label>
                            <input type="email" name="email" required value="<?= $data['old']['email'] ?? '' ?>">
                            <div class="text-danger small">
                                <?= $data['errors']['email'] ?? '' ?>
                            </div>

                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" required>
                            <div class="text-danger small">
                                <?= $data['errors']['confirm_password'] ?? '' ?>
                            </div>
                        </div>

                    </div>

                    <!-- Submit -->
                    <button type="submit" class="submit">
                        <span class="button-text">Create Account</span>
                        <span class="button-icon">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                    </button>

                    <!-- Login -->
                    <p class="text-center login-link ">Already have an account? <a href="<?= BASE_URL ?>Auth/login">Log
                            in</a>
                    </p>
                    <p class="text-center footer-text">By joining, you agree to our Terms of Service & Privacy Policy
                    </p>

                </form>
            </div>
        </div>
    </section>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
    <script src="<?= BASE_URL ?>assets/js/all.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>