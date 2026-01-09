<?php

namespace App\Repositories;
use App\Models\Trip;

interface ITripRepository
{
    public function getAllTrips(int $userId): array;
    public function getAllSharedTrip(int $userId): array;
    public function getAllFollowingTrip(int $userId): array;
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): int;
    public function getTripById(int $tripId): Trip;
    public function getTripAndUserNameById(int $userId, int $tripId): Trip;
    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void;
    public function deleteTrip(int $userId, int $tripId): void;
}