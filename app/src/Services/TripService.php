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

        if (!$trip) {
            throw new \Exception("Trip not found or you do not have access to it.");
        }

        return $trip;
    }
}