<?php

namespace App\Repositories;

use App\Models\User;

interface IUserRepository
{
    public function findByEmail(string $email) : ?User;
    public function createUser(string $username, string $firstName, string $lastName, string $email, string $password) : User;
}