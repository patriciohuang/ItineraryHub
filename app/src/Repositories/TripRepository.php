<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;

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
    
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): int
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
        return (int)$this->getConnection()->lastInsertId();
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
}