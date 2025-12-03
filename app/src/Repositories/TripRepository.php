<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;

class TripRepository extends Repository implements ITripRepository
{
    public function getAllTrips(): array
    {
        $sql = 'SELECT title, description, start_date, end_date FROM trips';
        $result = $this->getConnection()->query($sql);
        return $result->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }
}