<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center mt-5">

        <div class="card shadow-lg p-4" style="max-width: 500px; width: 100%;">
            
            <h3 class="text-center mb-4">Edit User</h3>

            <form action="<?= BASE_URL ?>Admin/update/<?= $data['user']['id'] ?>" method="POST">

                <!-- Name -->
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-control <?= isset($data['errors']['name']) ? 'is-invalid' : '' ?>"
                        value="<?= $data['user']['name'] ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['name'] ?? '' ?>
                    </div>
                </div>

                <!-- Age -->
                <div class="mb-3">
                    <label class="form-label">Age</label>
                    <input 
                        type="number" 
                        name="age" 
                        class="form-control <?= isset($data['errors']['age']) ? 'is-invalid' : '' ?>"
                        value="<?= $data['user']['age'] ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['age'] ?? '' ?>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-control <?= isset($data['errors']['email']) ? 'is-invalid' : '' ?>"
                        value="<?= $data['user']['email'] ?>"
                    >
                    <div class="invalid-feedback">
                        <?= $data['errors']['email'] ?? '' ?>
                    </div>
                </div>

                <!-- Update Button -->
                <button type="submit" class="btn btn-primary w-100">
                    Update
                </button>

            </form>

        </div>

    </div>
</body>