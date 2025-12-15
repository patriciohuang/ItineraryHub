<?php

namespace App\Services;

use App\Services\IUserService;
use App\Repositories\IUserRepository;
use App\Repositories\UserRepository;
use App\Models\User;

class UserService implements IUserService
{
    private IUserRepository $userRepository;
    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    
    public function findByEmail(string $email) : ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function registerUser(string $username, string $firstName, string $lastName, string $email, string $password) : User
    {
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser !== null) {
            throw new \Exception("User with this email already exists.");
        }

        $user = $this->userRepository->createUser($username, $firstName, $lastName, $email, $password);

        return $user;
    }
}