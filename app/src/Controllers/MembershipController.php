<?php

namespace App\Controllers;

use App\ViewModels\TripsViewModel;
use App\Services\IMembershipService; 
use App\Services\ITripItemService;
use App\Services\ITripService;

class MembershipController extends BaseController
{
    public function __construct(IMembershipService $membershipService, ITripItemService $tripItemService, ITripService $tripService)
    {
        parent::__construct($membershipService, $tripItemService, $tripService);
    }

    public function getInviteLinkAPI()
    {
        header('Content-Type: application/json');

        $tripId = $_GET['trip_id'] ?? null;
        $role = $_GET['role'] ?? 'COLLABORATOR';
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'User not logged in']);
            exit;
        }

        if (!$tripId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Trip ID is required']);
            exit;
        }

        try {
            $trip = $this->tripService->getTripById($tripId);
            
            if ($trip->added_by !== $userId) {
                http_response_code(403);
                echo json_encode(['success' => false, 'error' => 'Only the trip owner can generate invite links.']);
                exit;
            }

            $url = $this->generateInviteUrl($tripId, $role);

            echo json_encode(['success' => true, 'url' => $url]);
            exit;

        } catch (\Exception $e) {
            http_response_code(500); 
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
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

    public function joinConfirmationView()
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

        $existingMember = $this->membershipService->getTripMember($tripId, $userId);
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
                $this->membershipService->addMemberToTrip(
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
                $this->membershipService->updateOfferedRole($tripId, $userId, \App\Models\TripMembership::STATUS_PENDING, $roleOffered);
            }

            // I do this because the POST method strictly checks for a signature.
            // Since we trust this user (they are logged in + pending), give them a fresh one.
            $data = "trip_id={$tripId}&role={$roleOffered}";
            $signature = hash_hmac('sha256', $data, 'SECRET_APP_KEY');
            return $this->view([
                'trip' => $trip, 
                'roleOffered' => $roleOffered, 
                'signature' => $signature
            ], 'trip/joinConfirmationView');
            
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
            $this->membershipService->updateMemberStatus(
                $tripId, 
                $userId, 
                \App\Models\TripMembership::STATUS_REJECTED,
            );
            $_SESSION['success'] = "Invitation declined.";
            header("Location: /");
            exit;
        }

        try {
            $this->membershipService->updateMemberRole(
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