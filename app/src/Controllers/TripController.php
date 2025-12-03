<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;


class TripController
{
    private ITripService $tripService;

    public function __construct()
    {
        $this->tripService = new TripService();
    }

    public function home()
    {
        $trips = $this->tripService->getAllTrips();
        require_once __DIR__ . '/../views/home.php';
    }
}
