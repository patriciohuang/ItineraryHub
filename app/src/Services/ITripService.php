<?php

namespace App\Services;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

interface ITripService
{
    //Trip
    public function getAllTrips(int $userId): array;
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void;
    public function getTripById(int $userId, int $tripId): Trip;
    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void;
    //Trip item
    public function getTripItems(int $userId, int $tripId): array;
    public function getTripItemById(int $tripItemId): TripItem;
    public function createTripItem(int $tripId, int $categoryId, string $title, string $startDate, string $endDate, string $url, string $notes, int $userId): int;

    //Attachments and categories
    public function addAttachment(int $tripItemId, string $filePath, string $type): void;
    public function getAttachmentsByTripItemId(int $tripItemId): ?Attachment;
    public function getAllCategories(): array;
}