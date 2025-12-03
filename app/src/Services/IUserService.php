<?php

namespace App\Services;

use App\Models\User;

interface IUserService
{
    public function findByEmail(string $email) : ?User;
    public function registerUser(string $username, string $firstName, string $lastName, string $email, string $password) : User;
}