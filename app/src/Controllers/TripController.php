<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;
use App\Services\ITripItemService;
use App\Services\TripItemService;
use App\Services\IMembershipService;
use App\Services\MembershipService;
use App\ViewModels\TripsViewModel;


class TripController
{
    private ITripService $tripService;
    private ITripItemService $TripItemService;
    private IMembershipService $membershipService;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $this->tripService = new TripService();
        $this->TripItemService = new TripItemService();
        $this->membershipService = new MembershipService();
    }

    public function home()
    {
        $userId = $_SESSION['user_id'];
        $trips = $this->tripService->getAllTrips($userId);
        $pendingInvites = $this->tripService->getPendingInvites($userId);
        $pendingCount = count($pendingInvites);
        $vm = new TripsViewModel($trips);
        require __DIR__ . '/../Views/trip/Home.php';
    }

    public function seeSharedTrips()
    {
        $userId = $_SESSION['user_id'];
        $trips = $this->tripService->getAllSharedTrip($userId);
        $vm = new TripsViewModel($trips);
        require __DIR__ . '/../Views/trip/trip-shared.php';
    }

    public function seeFollowingTrips()
    {
        $userId = $_SESSION['user_id'];
        $trips = $this->tripService->getAllFollowingTrip($userId);
        $vm = new TripsViewModel($trips);
        require __DIR__ . '/../Views/trip/trip-following.php';
    }

    public function showAddTrip()
    {
        require __DIR__ . '/../Views/trip/trip-add.php';
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

        if (strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = 'Start date cannot be later than end date.';
            header('Location: /trip/add');
            exit;
        }
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

    public function seeTripDetail(array $params)
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
            $items = $this->TripItemService->getTripItems($id);
            $categories = $this->TripItemService->getAllCategories();
            $role = $this->membershipService->getTripMember($id, $userId);

            $currentUserId = $_SESSION['user_id'] ?? 0;
            $isOwner = ($trip->added_by === $currentUserId);
            $isParticipant = (is_array($role) && $role['role'] === 'PARTICIPANT');;

            $oldInput = $_SESSION['form_input'] ?? [];
            unset($_SESSION['form_input']);
            require __DIR__ . '/../Views/trip/trip-detail.php';
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
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        if (empty($title) || empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = "Title and Dates are required.";
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