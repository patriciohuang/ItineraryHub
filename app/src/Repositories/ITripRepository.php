<?php

namespace App\Repositories;
use App\Models\Trip;

interface ITripRepository
{
    public function getAllTrips(int $userId): array;
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void;
    public function getTripById(int $userId, int $tripId): Trip;
    public function getTripItems(int $userId, int $tripId): array;
    public function getAllCategories(): array;
    public function createTripItem(int $tripId, int $categoryId, string $title, string $startDate, string $endDate, string $url, string $notes, int $userId): int;
    public function addAttachment(int $tripItemId, string $filePath, string $type): void;
}