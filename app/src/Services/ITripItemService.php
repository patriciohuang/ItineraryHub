<?php  

namespace App\Services;
use App\Models\TripItem;
use App\Models\Attachment;

interface ITripItemService
{
    //Trip item
    public function getTripItems(int $tripId): array;
    public function getTripItemById(int $tripItemId): TripItem;
    public function createTripItem(int $tripId, int $categoryId, string $title, ?string $startDate, ?string $endDate, string $url, string $notes, int $userId): int;
    public function updateTripItem(int $tripItemId, int $categoryId, string $title, ?string $startDate, ?string $endDate, string $url, string $notes): void;
    public function deleteTripItem(int $userId, int $tripItemId): void;
    public function suggestItem(int $tripId, int $categoryId, string $title, ?string $startDate, ?string $endDate, string $url, string $notes, int $userId): int;
    public function approveSuggestedItem(int $itemId, int $userId): void;
    public function rejectSuggestedItem(int $itemId, int $userId): void;
    public function getPendingSuggestions(int $userId): array;

    //Participants
    public function addParticipantToItem(int $itemId, int $userId): void;
    public function getParticipantsByItemId(int $itemId): array;
    public function removeParticipantFromItem(int $itemId, int $userId): void;

    //Attachments and categories
    public function addAttachment(int $tripItemId, string $filePath, string $type): void;
    public function getAttachmentsByTripItemId(int $tripItemId): ?Attachment;
    public function getAllCategories(): array;
    public function updateAttachment(int $tripItemId, string $filePath, string $type): void;
}