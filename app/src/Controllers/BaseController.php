<?php

namespace App\Controllers;

use App\Services\IMembershipService;
use App\Services\MembershipService;
use App\Services\ITripItemService;
use App\Services\TripItemService;
use App\Services\ITripService;
use App\Services\TripService;

abstract class BaseController
{
    protected IMembershipService $membershipService;
    protected ITripItemService $tripItemService;
    protected ITripService $tripService;

    public function __construct(
        IMembershipService $membershipService,
        ITripItemService $tripItemService,
        ITripService $tripService
    )
    {
        $this->membershipService = $membershipService;
        $this->tripItemService = $tripItemService;
        $this->tripService = $tripService;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->enforceAuthentication();
    }

    protected function enforceAuthentication(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    protected function view(array $data = [], ?string $viewName = null)
    {
        if ($viewName === null) {
            // If the view name is not provided, it looks back 2 steps, and takes the controller method that called this view() function.
            $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
            // Then I need to extracts the function name from that caller. If TripController::index() called this, $methodName becomes "index".
            $methodName = $caller['function'];
            
            $reflection = new \ReflectionClass($this);
            $controllerName = strtolower(str_replace('Controller', '', $reflection->getShortName()));

            $viewName = "$controllerName/$methodName";
        }

        // Since every page extends BaseController, I can inject navbar data automatically here.
        if (isset($_SESSION['user_id'])) {
            [$pendingInvites, $pendingSuggestions, $totalNotifications] = $this->getNotificationData($_SESSION['user_id']);
            $data['pendingInvites'] = $pendingInvites;
            $data['pendingSuggestions'] = $pendingSuggestions;
            $data['totalNotifications'] = $totalNotifications;
        }
        extract($data);

        $viewPath = __DIR__ . "/../Views/$viewName.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            throw new \Exception("View file not found: $viewPath");
        }
    }

    protected function getNotificationData(int $userId): array
    {
        $pendingInvites = $this->membershipService->getPendingInvites($userId);
        $pendingSuggestions = $this->tripItemService->getPendingSuggestions($userId);
        $totalNotifications = count($pendingInvites) + count($pendingSuggestions);

        return [$pendingInvites, $pendingSuggestions, $totalNotifications];
    }
}