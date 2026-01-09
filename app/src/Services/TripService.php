<?php

namespace App\Services;

use App\Services\ITripService;
use App\Repositories\ITripRepository;
use App\Repositories\TripRepository;
use App\Models\Trip;

class TripService implements ITripService
{
    private ITripRepository $tripRepository;
    public function __construct()
    {
        $this->tripRepository = new TripRepository();
    }

    public function getAllTrips(int $userId): array
    {
        return $this->tripRepository->getAllTrips($userId);
    }

    public function getAllSharedTrip(int $userId): array
    {
        return $this->tripRepository->getAllSharedTrip($userId);
    }

    public function getAllFollowingTrip(int $userId): array
    {
        return $this->tripRepository->getAllFollowingTrip($userId);
    }

    public function createTrip(int $userId, string $title, string $description, ?string $startDate, ?string $endDate): int
    {
        return $this->tripRepository->createTrip($userId, $title, $description, $startDate, $endDate);
    }

    public function getTripById(int $tripId): Trip
    {
        return $this->tripRepository->getTripById($tripId);
    }

    public function getTripAndUserNameById(int $userId, int $tripId): Trip
    {
        return $this->tripRepository->getTripAndUserNameById($userId, $tripId);
    }

    public function updateTrip(int $tripId, string $title, string $description, ?string $startDate, ?string $endDate): void
    {
        $this->tripRepository->updateTrip($tripId, $title, $description, $startDate, $endDate);
    }

    public function deleteTrip(int $userId, int $tripId): void
    {
        if (!$this->tripRepository->getTripAndUserNameById($userId, $tripId)) {
            throw new \Exception("Trip not found or you do not have permission to delete this trip.");
        }
        $this->tripRepository->deleteTrip($userId, $tripId);
    }
}