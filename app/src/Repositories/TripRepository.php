<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

class TripRepository extends Repository implements ITripRepository
{
    public function getAllTrips(int $userId): array
    {
        $sql = 'SELECT id, title, description, start_date, end_date FROM trips WHERE added_by = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }

    public function getTripById(int $userId, int $tripId): Trip
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

    public function addMemberToTrip(int $tripId, int $userId, string $role, string $status): void
    {
        $sql = 'INSERT INTO trip_memberships (trip_id, user_id, role_offered, membership_status) VALUES (:trip_id, :user_id, :role, :status)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId,
            ':role' => $role,
            ':status' => $status
        ]);
    }
}