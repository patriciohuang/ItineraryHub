<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\IUserRepository;
use App\Models\User;

class UserRepository extends Repository implements IUserRepository
{
    public function findByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':email' => $email]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, User::class);
        $result = $statement->fetch();
        if ($result === false) {
            return null;
        }
        return $result;
    }

    public function createUser(string $username, string $firstName, string $lastName, string $email, string $password): User
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (username, first_name, last_name, email, password, created_at) VALUES (:username, :first_name, :last_name, :email, :password, :created_at)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':username' => $username,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':created_at' => date('Y-m-d H:i:s')
        ]);
        $userId = $this->getConnection()->lastInsertId();
        return new User($userId, $username, $firstName, $lastName, $email, $hashedPassword);
    }
}