<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;
use App\Services\ITripItemService;
use App\Services\TripItemService;
use App\ViewModels\TripsViewModel;


class TripController
{
    private ITripService $tripService;
    private ITripItemService $TripItemService;

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
        require __DIR__ . '/../Views/trip/shared-trip.php';
    }

    public function seeFollowingTrips()
    {
        $userId = $_SESSION['user_id'];
        $trips = $this->tripService->getAllFollowingTrip($userId);
        $vm = new TripsViewModel($trips);
        require __DIR__ . '/../Views/trip/following-trip.php';
    }

    public function showAddTrip()
    {
        require __DIR__ . '/../Views/trip/Add-trip.php';
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
            $this->tripService->createTrip($userId, $title, $description, $startDate, $endDate);

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
            $role = $this->tripService->getTripMember($id, $userId);

            $currentUserId = $_SESSION['user_id'] ?? 0;
            $isOwner = ($trip->added_by === $currentUserId);
            $isParticipant = (is_array($role) && $role['role'] === 'PARTICIPANT');;

            $oldInput = $_SESSION['form_input'] ?? [];
            unset($_SESSION['form_input']);
            require __DIR__ . '/../Views/trip/detail.php';
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

    private function generateInviteUrl(int $tripId, string $role): string 
    {
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'];
        $data = "trip_id={$tripId}&role={$role}";
        
        $signature = hash_hmac('sha256', $data, 'SECRET_APP_KEY');
        
        return "{$baseUrl}/trip/join?{$data}&sig={$signature}";
    }

    public function showJoinConfirmation()
    {
        $tripId = $_GET['trip_id'] ?? null;
        $roleOffered = $_GET['role'] ?? null;
        $signature = $_GET['sig'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "Please login to view this invitation.";
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header("Location: /login");
            exit;
        }

        $existingMember = $this->tripService->getTripMember($tripId, $userId);
        $isPending = $existingMember && $existingMember['membership_status'] === \App\Models\TripMembership::STATUS_PENDING;

        if (!$isPending) {
            $data = "trip_id={$tripId}&role={$roleOffered}";
            $expectedSignature = hash_hmac('sha256', $data, 'SECRET_APP_KEY');
    
            if (!hash_equals($expectedSignature, $signature)) {
                $_SESSION['error'] = "Invalid or expired invitation link.";
                header("Location: /");
                exit;
            }
        }

        try {
            $trip = $this->tripService->getTripById($tripId); 
            if ($trip->added_by === $userId) {
                $_SESSION['success'] = "You are the owner of this trip.";
                header("Location: /trip/$tripId");
                exit;
            }

            if (!$existingMember) {
                $this->tripService->addMemberToTrip(
                    $tripId, 
                    $userId,
                    $roleOffered,
                    \App\Models\TripMembership::STATUS_PENDING,
                    $trip->added_by
                );
            } 

            elseif ($existingMember['role'] === $roleOffered) {
                $_SESSION['success'] = "You are already a member.";
                header("Location: /trip/$tripId");
                exit;
            } else {
                $this->tripService->updateOfferedRole($tripId, $userId, \App\Models\TripMembership::STATUS_PENDING, $roleOffered);
            }

            // I do this because the POST method strictly checks for a signature.
            // Since we trust this user (they are logged in + pending), give them a fresh one.
            $data = "trip_id={$tripId}&role={$roleOffered}";
            $signature = hash_hmac('sha256', $data, 'SECRET_APP_KEY');
            require __DIR__ . '/../Views/trip/join-confirmation.php';
            
        } catch (\Exception $e) {
            $_SESSION['error'] = "Trip not found.";
            header("Location: /");
            exit;
        }
    }

    public function processJoinDecision()
    {
        $tripId = $_POST['trip_id'] ?? null;
        $role = $_POST['role'] ?? null;
        $signature = $_POST['sig'] ?? null;
        $decision = $_POST['decision'] ?? 'reject';
        $userId = $_SESSION['user_id'];

        $data = "trip_id={$tripId}&role={$role}";
        $expectedSignature = hash_hmac('sha256', $data, 'SECRET_APP_KEY'); 

        if (!hash_equals($expectedSignature, $signature)) {
            die("Invalid request signature.");
        }

        if ($decision === 'reject') {
            $this->tripService->updateMemberStatus(
                $tripId, 
                $userId, 
                \App\Models\TripMembership::STATUS_REJECTED,
            );
            $_SESSION['success'] = "Invitation declined.";
            header("Location: /");
            exit;
        }

        try {
            $this->tripService->updateMemberRole(
                $tripId, 
                $userId, 
                \App\Models\TripMembership::STATUS_ACCEPTED, 
                $role
            );
            
            $_SESSION['success'] = "Welcome aboard! You joined as a " . strtolower($role) . ".";
            header("Location: /trip/$tripId");
            exit;

        } catch (\Exception $e) {
            $_SESSION['error'] = "Error joining trip: " . $e->getMessage();
            header("Location: /");
            exit;
        }
    }
}