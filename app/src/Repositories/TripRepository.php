<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;

class TripRepository extends Repository implements ITripRepository
{
    public function getAllTrips(int $userId): array
    {
        $sql = 'SELECT title, description, start_date, end_date FROM trips WHERE id = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }
}