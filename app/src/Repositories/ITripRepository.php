<?php

namespace App\Repositories;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

interface ITripRepository
{
    //Trip
    public function getAllTrips(int $userId): array;
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void;
    public function getTripById(int $userId, int $tripId): Trip;
    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void;
    public function deleteTrip(int $userId, int $tripId): void;

    public function getTripMember(int $tripId, int $userId);
    public function addMemberToTrip(int $tripId, int $userId, string $role, string $status): void;
}