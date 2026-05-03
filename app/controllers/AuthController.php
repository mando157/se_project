<?php

require_once "../app/helpers/Validator.php";
require_once "../app/helpers/Auth.php";
require_once "../app/models/User.php";

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /* SHOW REGISTER PAGE */
    public function register()
    {
        $this->view("auth/register");
    }

    /* HANDLE REGISTER */
    public function storeRegister()
    {
        $validator = new Validator();

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';

        // Validation
        $validator->required('name', $name);
        $validator->required('email', $email);
        $validator->email('email', $email);
        $validator->required('password', $password);
        $validator->minLength('password', $password, 6, 'Password must be at least 6 characters.');
        $validator->required('confirm_password', $confirm);
        $validator->required('role', $role, 'Please select a role.');

        if ($password !== $confirm) {
            $validator->setError('confirm_password', 'Passwords do not match');
        }

        if ($this->userModel->emailExists($email)) {
            $validator->setError('email', 'Email already exists');
        }

        if (!$validator->passes()) {
            $this->view("auth/register", [
                'errors' => $validator->getErrors(),
                'old' => $_POST
            ]);
            return;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Save user
        $this->userModel->createUser($name, $email, $hashedPassword, $role);

        header("Location: " . BASE_URL . "Auth/login");
        exit;
    }

    /* SHOW LOGIN */
    public function login()
    {
        $this->view("auth/login");
    }

    /* HANDLE LOGIN */
    public function doLogin()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->view("auth/login", [
                "errors" => ["login" => "Invalid email or password"],
                "old" => $_POST
            ]);
            return;
        }

        Auth::login($user);

        switch ($user['role']) {
            case 'admin':
                header("Location: " . BASE_URL . "Admin/index");
                break;
            case 'driver':
                header("Location: " . BASE_URL . "Driver/index");
                break;
            case 'owner':
                header("Location: " . BASE_URL . "Owner/index");
                break;
            default:
                header("Location: " . BASE_URL . "Home/index");
        }

        exit;
    }

    public function logout()
    {
        Auth::logout();
        header("Location: " . BASE_URL . "Auth/login");
        exit;
    }
}