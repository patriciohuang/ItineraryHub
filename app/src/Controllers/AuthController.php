<?php

namespace App\Controllers;

use App\Services\UserService;

class AuthController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function showLogin()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please fill all fields";
            header('Location: /login');
            exit;
        }

        $user = $this->userService->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            
            $_SESSION['success'] = "Welcome back, " . htmlspecialchars($user->username) . "!";
            header('Location: /');
            exit;
        } else {
            $_SESSION['error'] = "Invalid email or password";
            header('Location: /login');
            exit;
        }
    }

    public function logout()
    {
        try {
            session_destroy();
            $_SESSION['success'] = "You have been logged out successfully.";
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "An error occurred while logging out.";
        }
        
    }

    public function showRegister()
    {
        require __DIR__ . '/../Views/auth/register.php';
    }

    public function register()
    {
        $username = $_POST['username'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please fill all fields";
            header('Location: /register');
            exit;
        }

        try {
            $this->userService->registerUser($username, $firstName, $lastName, $email, $password);
            $_SESSION['success'] = "Registration successful. Please log in.";
            header('Location: /login');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "An error occurred ". $e->getMessage();
            header('Location: /register'); 
            exit;
        }
    }
}