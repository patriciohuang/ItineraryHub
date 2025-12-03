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
        $this->tripRepository = new TripRepository();
    }
    
    public function getAllTrips(): array
    {
        return $this->tripRepository->getAllTrips();
    }
}