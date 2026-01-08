<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

class TripRepository extends Repository implements ITripRepository
{
    public function getPendingInvites(int $userId): array
    {
        $sql = 'SELECT tm.*, t.title FROM trip_memberships tm
            JOIN trips t ON tm.trip_id = t.id
            WHERE tm.user_id = :user_id AND tm.membership_status = "PENDING"';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllTrips(int $userId): array
    {
        $sql = 'SELECT * FROM trips WHERE added_by = :user_id
            ORDER BY start_date ASC';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }

    public function getAllSharedTrip(int $userId): array
    {
        $sql = 'SELECT t.* FROM trips t
            JOIN trip_memberships tm ON t.id = tm.trip_id
            WHERE tm.user_id = :user_id AND tm.membership_status = "ACCEPTED" AND tm.role = "PARTICIPANT"
            ORDER BY t.start_date ASC';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }

    public function getAllFollowingTrip(int $userId): array
    {
        $sql = 'SELECT t.* FROM trips t
            JOIN trip_memberships tm ON t.id = tm.trip_id
            WHERE tm.user_id = :user_id AND tm.membership_status = "ACCEPTED" AND tm.role = "COLLABORATOR"
            ORDER BY t.start_date ASC';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }

    public function getTripById(int $tripId): Trip
    {
        $sql = 'SELECT * FROM trips WHERE id = :id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':id' => $tripId
        ]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, Trip::class);
        $trip = $statement->fetch();
        return $trip? : null;
    }   

    public function getTripAndUserNameById(int $userId, int $tripId): Trip
    {
        $sql = 'SELECT t.*, u.username as owner_name, u.email as owner_email 
            FROM trips t
            LEFT JOIN users u ON t.added_by = u.id
            WHERE t.id = :id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':id' => $tripId
        ]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, Trip::class);
        $trip = $statement->fetch();
        return $trip? : null;
    }

    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void
    {
        $sql = 'UPDATE trips SET title = :title, description = :description, start_date = :start_date, end_date = :end_date WHERE id = :trip_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':trip_id' => $tripId
        ]);
    }
    
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void
    {
        $sql = 'INSERT INTO trips (title, description, start_date, end_date, added_by) VALUES (:title, :description, :start_date, :end_date, :added_by)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':added_by' => $userId
        ]);
    }

    public function deleteTrip(int $userId, int $tripId): void
    {
        $sql = 'DELETE FROM trips WHERE id = :trip_id AND added_by = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId
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
}