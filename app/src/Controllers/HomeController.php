<?php

namespace App\Controllers;

use App\ViewModels\TripsViewModel;
use App\Services\IMembershipService; 
use App\Services\ITripItemService;
use App\Services\ITripService;

class HomeController extends BaseController
{
    public function __construct(IMembershipService $membershipService, ITripItemService $tripItemService, ITripService $tripService)
    {
        parent::__construct($membershipService, $tripItemService, $tripService);
    }

    public function homeView()
    {
        $userId = $_SESSION['user_id'];
        
        $trips = $this->tripService->getAllTrips($userId);
        $vm = new TripsViewModel($trips);

        return $this->view(['vm' => $vm]);
    }

    public function sharedTripsView()
    {
        $userId = $_SESSION['user_id'];
        
        $trips = $this->tripService->getAllSharedTrip($userId);
        $vm = new TripsViewModel($trips);
        return $this->view(['vm' => $vm]);
    }

    public function followingTripsView()
    {
        $userId = $_SESSION['user_id'];
        
        $trips = $this->tripService->getAllFollowingTrip($userId);
        $vm = new TripsViewModel($trips);
        return $this->view(['vm' => $vm]);
    }

    public function notificationsView()
    {
        $userId = $_SESSION['user_id'];
        return $this->view(['notifications']);
    }
}