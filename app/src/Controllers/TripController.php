<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;


class TripController
{
    private ITripService $tripService;

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
    }

    public function home()
    {
        $userId = $_SESSION['user_id'];
        $trips = $this->tripService->getAllTrips($userId);
        require __DIR__ . '/../Views/trip/Home.php';
    }

    public function showAddTrip()
    {
        require __DIR__ . '/../Views/trip/Add-trip.php';
    }
}
