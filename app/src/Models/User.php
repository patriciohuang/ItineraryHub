<?php

namespace App\Models;

class User {
    public int $id;
    public ?string $first_name = null;
    public ?string $last_name = null;
    public string $email;
    public string $password;
    public string $username;
    public string $created_at;
}