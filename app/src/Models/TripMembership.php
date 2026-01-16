<?php

namespace App\Models;

class TripMembership {
    public const STATUS_INVITED = 'INVITED';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_ACCEPTED = 'ACCEPTED';
    public const STATUS_REJECTED = 'REJECTED';

    public const ROLE_ADMIN = 'ADMIN';
    public const ROLE_COLLABORATOR = 'COLLABORATOR';
    public const ROLE_PARTICIPANT = 'PARTICIPANT';

    public int $id;
    public int $trip_id;
    public int $user_id;
    public string $membership_status;
    public string $role;
    public ?string $role_offered;
    public ?int $invited_by;
    public string $username;
    public string $first_name;
    public string $last_name;
    public ?string $title = null;
}