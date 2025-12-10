<?php

namespace App\Models;

class User {
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $email;
    public string $password;
    public string $username;
    public string $created_at;
}