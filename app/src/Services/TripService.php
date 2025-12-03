<?php

namespace App\Services;

use App\Services\ITripService;
use App\Repositories\ITripRepository;
use App\Repositories\TripRepository;

class TripService implements ITripService
{
    private ITripRepository $tripRepository;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {

            header('Location: /login');
            exit;
        }
        $this->tripRepository = new TripRepository();
    }
    
    public function getAllTrips(int $userId): array
    {
        return $this->tripRepository->getAllTrips($userId);
    }
}