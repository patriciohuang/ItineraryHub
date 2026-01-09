<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;
use App\ViewModels\TripsViewModel;

class HomeController extends BaseController
{
    private ITripService $tripService;

    public function __construct()
    {
        parent::__construct(); 
        
        $this->tripService = new TripService();
    }

    public function home()
    {
        $userId = $_SESSION['user_id'];
        
        $trips = $this->tripService->getAllTrips($userId);
        $vm = new TripsViewModel($trips);

        list($pendingInvites, $pendingSuggestions, $totalNotifications) = $this->getNotificationData($userId);

        require __DIR__ . '/../Views/trip/home.php';
    }

    public function seeSharedTrips()
    {
        $userId = $_SESSION['user_id'];
        
        $trips = $this->tripService->getAllSharedTrip($userId);
        $vm = new TripsViewModel($trips);

        list($pendingInvites, $pendingSuggestions, $totalNotifications) = $this->getNotificationData($userId);

        require __DIR__ . '/../Views/trip/trip-shared.php';
    }

    public function seeFollowingTrips()
    {
        $userId = $_SESSION['user_id'];
        
        $trips = $this->tripService->getAllFollowingTrip($userId);
        $vm = new TripsViewModel($trips);

        list($pendingInvites, $pendingSuggestions, $totalNotifications) = $this->getNotificationData($userId);

        require __DIR__ . '/../Views/trip/trip-following.php';
    }

    public function notifications()
    {
        $userId = $_SESSION['user_id'];
        
        list($pendingInvites, $pendingSuggestions, $totalNotifications) = $this->getNotificationData($userId);

        require __DIR__ . '/../Views/trip/notifications.php';
    }
}