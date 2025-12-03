<?php

namespace App\Repositories;

interface ITripRepository
{
    public function getAllTrips(int $userId): array;
}