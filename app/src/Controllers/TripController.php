<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;
use App\ViewModels\TripsViewModel;


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
        $vm = new TripsViewModel($trips);
        require __DIR__ . '/../Views/trip/Home.php';
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
            header('Location: /add-trip');
            exit;
        }

        if(empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = 'Start date and end date are required.';
            header('Location: /add-trip');
            exit;
        }

        if(strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = 'Start date cannot be later than end date.';
            header('Location: /add-trip');
            exit;
        }
        
        $this->tripService->createTrip($userId, $title, $description, $startDate, $endDate);

        $_SESSION['success'] = 'Trip created successfully.';
        header('Location: /');
        exit;
    }

    public function seeTripDetail(array $params)
    {
        $id = $params['id'] ?? null;
        if ($id === null) {
            $_SESSION['error'] = 'Trip ID is required.';
            header('Location: /');
            exit;
        }
        try {
            $userId = $_SESSION['user_id'];
            $trip = $this->tripService->getTripById($userId, $id);
            require __DIR__ . '/../Views/trip/detail.php';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /');
            exit;
        }
    }
}
