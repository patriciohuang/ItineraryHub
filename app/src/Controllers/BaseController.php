<?php

namespace App\Controllers;

use App\Services\IMembershipService;
use App\Services\MembershipService;
use App\Services\ITripItemService;
use App\Services\TripItemService;

abstract class BaseController
{
    protected IMembershipService $membershipService;
    protected ITripItemService $tripItemService;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $this->membershipService = new MembershipService();
        $this->tripItemService = new TripItemService();
    }

    protected function getNotificationData(int $userId): array
    {
        $pendingInvites = $this->membershipService->getPendingInvites($userId);
        $pendingSuggestions = $this->tripItemService->getPendingSuggestions($userId);
        $totalNotifications = count($pendingInvites) + count($pendingSuggestions);

        return [$pendingInvites, $pendingSuggestions, $totalNotifications];
    }
}