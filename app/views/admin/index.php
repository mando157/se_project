<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">

</head>

<body class="bg-light">
    
    <!-- ================= HEADER / NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <!-- Website Logo/Name -->
            <a class="navbar-brand">
                My PHP MVC Project
            </a>

            <!-- Menu -->
            <div>

                <!-- Right Side Buttons -->
                <div class="ms-auto d-flex gap-2">

                    <?php if(!$data['user']): ?>
                    <a href="<?= BASE_URL ?>Auth/login" class="btn btn-outline-light">
                        Login
                    </a>

                    <a href="<?= BASE_URL ?>Auth/register" class="btn btn-warning">
                        Register
                    </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>Auth/logout" class="btn btn-danger">
                            Logout
                        </a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>
    <!-- =============== END HEADER =============== -->

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Users</h1>

            <a href="<?= BASE_URL ?>Admin/create" class="btn btn-primary">
                + Create New User
            </a>
        </div>

        <div class="card shadow">
            <div class="card-body p-0">

                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Age</th>
                            <th style="width: 180px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($data['users'] as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= $user['age'] ?></td>

                                <td>
                                    <a href="<?= BASE_URL ?>Admin/show/<?= $user['id'] ?>" class="btn btn-sm btn-info text-white">
                                        View
                                    </a>

                                    <a href="<?= BASE_URL ?>Admin/edit/<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <a href="<?= BASE_URL ?>Admin/delete/<?= $user['id'] ?>" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this user?')">
                                    Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                </table>

            </div>
        </div>

    </div>
</body>