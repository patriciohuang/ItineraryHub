<?php

namespace App\Repositories;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

interface ITripRepository
{
    //Trip
    public function getPendingInvites(int $userId): array;
    public function getAllTrips(int $userId): array;
    public function getAllSharedTrip(int $userId): array;
    public function getAllFollowingTrip(int $userId): array;
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): int;
    public function getTripById(int $tripId): Trip;
    public function getTripAndUserNameById(int $userId, int $tripId): Trip;
    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void;
    public function deleteTrip(int $userId, int $tripId): void;

    //Memberships
    public function createMembership(int $tripId, int $userId, string $status,  string $role): void;
    public function getTripMember(int $tripId, int $userId);
    public function addMemberToTrip(int $tripId, int $userId, string $roleOffered, string $status, int $tripOwner): void;
    public function updateMemberRole(int $tripId, int $userId, string $status, string $role): void;
    public function updateOfferedRole(int $tripId, int $userId, string $status, string $roleOffered): void;
    public function updateMemberStatus(int $tripId, int $userId, string $status): void;
}