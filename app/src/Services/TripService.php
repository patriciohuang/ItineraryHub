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

    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void
    {
        $this->tripRepository->createTrip($userId, $title, $description, $startDate, $endDate);
    }

    public function getTripById(int $userId, int $tripId): Trip
    {
        $trip = $this->tripRepository->getTripById($userId, $tripId);
        return $trip;
    }

    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void
    {
        $this->tripRepository->updateTrip($tripId, $title, $description, $startDate, $endDate);
    }

    public function deleteTrip(int $userId, int $tripId): void
    {
        if (!$this->tripRepository->getTripById($userId, $tripId)) {
            throw new \Exception("Trip not found or you do not have permission to delete this trip.");
        }
        $this->tripRepository->deleteTrip($userId, $tripId);
    }

    public function getTripMember(int $tripId, int $userId)
    {
        return $this->tripRepository->getTripMember($tripId, $userId);
    }

    public function addMemberToTrip(int $tripId, int $userId, string $role, string $status): void
    {
        $this->tripRepository->addMemberToTrip($tripId, $userId, $role, $status);
    }
}