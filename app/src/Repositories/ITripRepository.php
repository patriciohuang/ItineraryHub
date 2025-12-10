<?php

namespace App\Repositories;
use App\Models\Trip;

interface ITripRepository
{
    public function getAllTrips(int $userId): array;
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void;
    public function getTripById(int $userId, int $tripId): Trip;
}