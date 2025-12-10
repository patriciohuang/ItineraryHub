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
    public int $tripId;
    public int $userId;
    public string $membershipStatus;
    public string $role;
    public string $roleOffered;
    public int $invitedBy;
}