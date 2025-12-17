<?php

namespace App\Services;

use App\Services\ITripService;
use App\Repositories\ITripRepository;
use App\Repositories\TripRepository;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

class TripService implements ITripService
{
    private ITripRepository $tripRepository;
    public function __construct()
    {
        $this->tripRepository = new TripRepository();
    }
    
    //Trip methods
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

    //Trip item methods
    public function createTripItem(int $tripId, int $categoryId, string $title, string $startDate, string $endDate, string $url, string $notes, int $userId): int
    {
        return $this->tripRepository->createTripItem($tripId, $categoryId, $title, $startDate, $endDate, $url, $notes, $userId);
    }

    public function getTripItems(int $userId, int $tripId): array
    {
        $this->getTripById($userId, $tripId);
        return $this->tripRepository->getTripItems($userId, $tripId);
    }

    public function getTripItemById(int $tripItemId): TripItem
    {
        return $this->tripRepository->getTripItemById($tripItemId);
    }

    public function getAllCategories(): array
    {
        return $this->tripRepository->getAllCategories();
    }

    public function addAttachment(int $tripItemId, string $filePath, string $type): void
    {
        $this->tripRepository->addAttachment($tripItemId, $filePath, $type);
    }
    public function getAttachmentsByTripItemId(int $tripItemId): ?Attachment
    {
        return $this->tripRepository->getAttachmentsByTripItemId($tripItemId);
    }
}