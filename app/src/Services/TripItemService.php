<?php

namespace App\Services;

use App\Services\ITripItemService;
use App\Repositories\ITripItemRepository;
use App\Repositories\TripItemRepository;
use App\Models\TripItem;
use App\Models\Attachment;

class TripItemService implements ITripItemService
{
    private ITripItemRepository $tripItemRepository;
    public function __construct()
    {
        $this->tripItemRepository = new TripItemRepository();
    }
    public function createTripItem(int $tripId, int $categoryId, string $title, string $startDate, string $endDate, string $url, string $notes, int $userId): int
    {
        return $this->tripItemRepository->createTripItem($tripId, $categoryId, $title, $startDate, $endDate, $url, $notes, $userId);
    }

    public function getTripItems(int $tripId): array
    {
        return $this->tripItemRepository->getTripItems($tripId);
    }

    public function getTripItemById(int $tripItemId): TripItem
    {
        return $this->tripItemRepository->getTripItemById($tripItemId);
    }

    public function updateTripItem(int $tripItemId, int $categoryId, string $title, string $startDate, string $endDate, string $url, string $notes): void
    {
        $this->tripItemRepository->updateTripItem($tripItemId, $categoryId, $title, $startDate, $endDate, $url, $notes);
    }

    public function deleteTripItem(int $userId, int $tripItemId): void
    {
        $this->tripItemRepository->deleteTripItem($userId, $tripItemId);
    }

    public function getAllCategories(): array
    {
        return $this->tripItemRepository->getAllCategories();
    }

    public function addAttachment(int $tripItemId, string $filePath, string $type): void
    {
        $this->tripItemRepository->addAttachment($tripItemId, $filePath, $type);
    }

    public function getAttachmentsByTripItemId(int $tripItemId): ?Attachment
    {
        return $this->tripItemRepository->getAttachmentsByTripItemId($tripItemId);
    }
}