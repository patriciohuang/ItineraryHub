<?php

namespace App\Controllers;

use App\ViewModels\TripsViewModel;
use App\Services\IMembershipService; 
use App\Services\ITripItemService;
use App\Services\ITripService;

class TripController extends BaseController
{
    public function __construct(IMembershipService $membershipService, ITripItemService $tripItemService, ITripService $tripService)
    {
        parent::__construct($membershipService, $tripItemService, $tripService);
    }

    public function addTripView()
    {
        return $this->view();
    }

    public function addTrip()
    {
        $userId = $_SESSION['user_id'];
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';

        if (empty($title)) {
            $_SESSION['error'] = 'Trip title is required.';
            header('Location: /trip/add');
            exit;
        }

        if (empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = 'Start date and end date are required.';
            header('Location: /trip/add');
            exit;
        }

        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = 'Start date cannot be later than end date.';
            header('Location: /trip/add');
            exit;
        }
        $oldInput = $_SESSION['form_input'] ?? [];
        unset($_SESSION['form_input']);
        try
        {
            $tripId = $this->tripService->createTrip($userId, $title, $description, $startDate, $endDate);
            $this->membershipService->createMembership($tripId, $userId,'ACCEPTED', 'ADMIN');

            $_SESSION['success'] = 'Trip created successfully.';
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating trip: ' . $e->getMessage();
            header('Location: /trip/add');
            exit;
        }
    }

    public function tripDetailView(array $params)
    {
        $id = $params['id'] ?? null;
        $origin = $_GET['from'] ?? 'home';
        if ($id === null) {
            $_SESSION['error'] = 'Trip ID is required.';
            header('Location: /');
            exit;
        }
        try {
            $userId = $_SESSION['user_id'];
            $trip = $this->tripService->getTripAndUserNameById($userId, $id);
            $items = $this->tripItemService->getTripItems($id);
            $categories = $this->tripItemService->getAllCategories();
            $role = $this->membershipService->getTripMember($id, $userId);
            $memberRole = $role['role'] ?? null; 
            $isParticipant = ($memberRole === 'PARTICIPANT');

            $currentUserId = $_SESSION['user_id'] ?? 0;
            $isOwner = ($trip->added_by === $currentUserId);

            list($pendingInvites, $pendingSuggestions, $totalNotifications) = $this->getNotificationData($userId);

            $oldInput = $_SESSION['form_input'] ?? [];
            unset($_SESSION['form_input']);
            return $this->view([
                'trip' => $trip,
                'items' => $items,
                'categories' => $categories,
                'isOwner' => $isOwner,
                'isParticipant' => $isParticipant,
                'origin' => $origin,
                'pendingInvites' => $pendingInvites,
                'pendingSuggestions' => $pendingSuggestions,
                'totalNotifications' => $totalNotifications,
                'oldInput' => $oldInput
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function editTripDetail(array $params)
    {
        $tripId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        $title = $_POST['title'];
        $description = $_POST['description'];
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';

        if (empty($title) || empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = "Title, start date, and end date are required.";
            header("Location: /trip/$tripId");
            exit;
        }
        if (strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = "Start date cannot be later than end date.";
            header("Location: /trip/$tripId");
            exit;
        }

        try {
            $this->tripService->updateTrip($tripId, $title, $description, $startDate, $endDate);
            
            $_SESSION['success'] = "Trip updated successfully!";
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error updating trip: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }

    public function deleteTrip(array $params)
    {
        $tripId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        try {
            $this->tripService->deleteTrip($userId, $tripId);
            $_SESSION['success'] = "Trip deleted successfully!";
            header("Location: /");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error deleting trip: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }
}