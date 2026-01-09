<?php

namespace App\Services;

interface IMembershipService
{
    public function createMembership(int $tripId, int $userId, string $status,  string $role): void;
    public function getTripMember(int $tripId, int $userId);
    public function addMemberToTrip(int $tripId, int $userId, string $roleOffered, string $status, int $tripOwner): void;
    public function updateMemberRole(int $tripId, int $userId, string $status, string $role): void;
    public function updateOfferedRole(int $tripId, int $userId, string $status, string $roleOffered): void;
    public function updateMemberStatus(int $tripId, int $userId, string $status): void;
    public function getPendingInvites(int $userId): array;
}