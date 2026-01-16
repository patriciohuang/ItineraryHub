<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\IMembershipRepository;
use App\Models\TripMembership;

class MembershipRepository extends Repository implements IMembershipRepository
{
    public function createMembership(int $tripId, int $userId, string $status,  string $role): void
    {
        $sql = 'INSERT INTO trip_memberships (trip_id, user_id, membership_status, role) VALUES (:trip_id, :user_id, :membership_status, :role)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId,
            ':membership_status' => $status,
            ':role' => $role
        ]);
    }
    
    public function getTripMember(int $tripId, int $userId)
    {
        $sql = 'SELECT * FROM trip_memberships WHERE trip_id = :trip_id AND user_id = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function addMemberToTrip(int $tripId, int $userId, string $roleOffered, string $status, int $tripOwner): void
    {
        $sql = 'INSERT INTO trip_memberships (trip_id, user_id,  membership_status, role_offered, invited_by) VALUES (:trip_id, :user_id, :membership_status, :role_offered, :trip_owner)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId,
            ':membership_status' => $status,
            ':role_offered' => $roleOffered,
            ':trip_owner' => $tripOwner
        ]);
    }

    public function updateMemberRole(int $tripId, int $userId, string $status, string $role): void
    {
        $sql = 'UPDATE trip_memberships SET role = :role, membership_status = :membership_status WHERE trip_id = :trip_id AND user_id = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':role' => $role,
            ':membership_status' => $status,
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
    }

    public function updateOfferedRole(int $tripId, int $userId, string $status, string $roleOffered): void
    {
        $sql = 'UPDATE trip_memberships SET role_offered = :role_offered, membership_status = :membership_status WHERE trip_id = :trip_id AND user_id = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':role_offered' => $roleOffered,
            ':membership_status' => $status,
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
    }

    public function updateMemberStatus(int $tripId, int $userId, string $status): void
    {
        $sql = 'UPDATE trip_memberships SET membership_status = :membership_status WHERE trip_id = :trip_id AND user_id = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':membership_status' => $status,
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
    }

    public function getPendingInvites(int $userId): array
    {
        $sql = 'SELECT tm.*, t.title FROM trip_memberships tm
            JOIN trips t ON tm.trip_id = t.id
            WHERE tm.user_id = :user_id AND tm.membership_status = "PENDING"';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, \App\Models\TripMembership::class);
    }

    public function getMembersByTripId(int $tripId): array
    {
        $sql = 'SELECT tm.*, u.username, u.first_name, u.last_name FROM trip_memberships tm
            JOIN users u ON tm.user_id = u.id
            WHERE tm.trip_id = :trip_id AND tm.membership_status = "ACCEPTED" AND tm.role = "PARTICIPANT"';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':trip_id' => $tripId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, \App\Models\TripMembership::class);
    }
}