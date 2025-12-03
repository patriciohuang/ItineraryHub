<?php

namespace App\Services;

interface ITripService
{
    public function getAllTrips(int $userId): array;
}