<?php
require_once "../app/helpers/Validator.php";
require_once "../app/models/User.php";
require_once "../app/helpers/Auth.php";

class AdminController extends Controller
{
    public function __construct()
    {
        Auth::redirectIfNotLogged();
        Auth::forbidIfNotRole("admin");

        $this->userModel = new User(); // model
    }

    // READ ALL
    public function index()
    {
        $user = Auth::user();

        $users = $this->userModel->getAllUsers();
        $this->view("admin/index", ['users' => $users, 'user' => $user]);
    }

    // SHOW ONE USER
    public function show($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("admin/show", ['user' => $user]);
    }

    // SHOW CREATE FORM
    public function create()
    {
        $this->view("admin/create");
    }

    // STORE NEW USER
    public function store()
    {
        $validator = new Validator();

        $name  = $_POST['name'];
        $age   = $_POST['age'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role  = $_POST['role'];

        // Validation rules
        $validator->required('name', $name);
        $validator->required('age', $age);
        $validator->required('email', $email);
        $validator->email('email', $email);
        $validator->required('password', $password);

        if ($validator->passes()) {
            // Hash password (bcrypt)
            $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            // Save to DB
            $this->userModel->createUser($name, $age, $email, $hashedPassword, $role);
            header("Location: " . BASE_URL . "Admin/index");
        } else {
            // Return errors to view
            $this->view("admin/create", [
                'errors' => $validator->getErrors(),
                'old'    => $_POST
            ]);
        }
    }

    // EDIT FORM
    public function edit($id)
    {
        $user = $this->userModel->getUserById($id);
        $this->view("admin/edit", ['user' => $user]);
    }

    // UPDATE USER
    public function update($id)
    {
        $name  = $_POST['name'];
        $email = $_POST['email'];
        $age   = $_POST['age'];

        $this->userModel->updateUser($id, $name, $age, $email);

        header("Location: " . BASE_URL . "Admin/index");
    }

    // DELETE USER
    public function delete($id)
    {
        $this->userModel->deleteUser($id);

        header("Location: " . BASE_URL . "Admin/index");
    }
}
