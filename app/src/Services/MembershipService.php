<?php

namespace App\Services;

use App\Services\IMembershipService;
use App\Repositories\IMembershipRepository;
use App\Repositories\MembershipRepository;

class MembershipService implements IMembershipService
{
    private IMembershipRepository $membershipRepository;
    public function __construct()
    {
        $this->membershipRepository = new MembershipRepository();
    }
    
    public function createMembership(int $tripId, int $userId, string $status,  string $role): void
    {
        $this->membershipRepository->createMembership($tripId, $userId, $status, $role);
    }

    public function getTripMember(int $tripId, int $userId)
    {
        return $this->membershipRepository->getTripMember($tripId, $userId);
    }

    public function addMemberToTrip(int $tripId, int $userId, string $roleOffered, string $status, int $tripOwner): void
    {
        $this->membershipRepository->addMemberToTrip($tripId, $userId, $roleOffered, $status, $tripOwner);
    }

    public function updateMemberRole(int $tripId, int $userId, string $status, string $role): void
    {
        $this->membershipRepository->updateMemberRole($tripId, $userId, $status, $role);
    }

    public function updateOfferedRole(int $tripId, int $userId, string $status, string $roleOffered): void
    {
        $this->membershipRepository->updateOfferedRole($tripId, $userId, $status, $roleOffered);
    }
    public function updateMemberStatus(int $tripId, int $userId, string $status): void
    {
        $this->membershipRepository->updateMemberStatus($tripId, $userId, $status);
    }
    public function getPendingInvites(int $userId): array
    {
        return $this->membershipRepository->getPendingInvites($userId);
    }
}