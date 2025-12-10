<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;

class TripRepository extends Repository implements ITripRepository
{
    public function getAllTrips(int $userId): array
    {
        $sql = 'SELECT id, title, description, start_date, end_date FROM trips WHERE added_by = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
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

    public function getTripById(int $userId, int $tripId): Trip
    {
        $sql = 'SELECT id, title, description, start_date, end_date, added_by FROM trips WHERE id = :trip_id AND added_by = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, Trip::class);
        $trip = $statement->fetch();
        return $trip? : null;
    }
}