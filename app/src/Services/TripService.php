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

    public function getTripById(int $tripId): Trip
    {
        return $this->tripRepository->getTripById($tripId);
    }

    public function getTripAndUserNameById(int $userId, int $tripId): Trip
    {
        return $this->tripRepository->getTripAndUserNameById($userId, $tripId);
    }

    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void
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

    public function getTripMember(int $tripId, int $userId)
    {
        return $this->tripRepository->getTripMember($tripId, $userId);
    }

    public function addMemberToTrip(int $tripId, int $userId, string $roleOffered, string $status, int $tripOwner): void
    {
        $this->tripRepository->addMemberToTrip($tripId, $userId, $roleOffered, $status, $tripOwner);
    }

    public function updateMemberRole(int $tripId, int $userId, string $status, string $role): void
    {
        $this->tripRepository->updateMemberRole($tripId, $userId, $status, $role);
    }

    public function updateOfferedRole(int $tripId, int $userId, string $status, string $roleOffered): void
    {
        $this->tripRepository->updateOfferedRole($tripId, $userId, $status, $roleOffered);
    }
}