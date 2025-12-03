<?php

namespace App\ViewModels;

use App\Models\Trip;

class TripViewModel
{
    /** @var Trip */
    public $title;
    public $description;
    public $startDate;
    public $endDate;

    public function __construct(Trip $trip)
    {
        $this->title = $trip->title;
        $this->description = $trip->description;
        $this->startDate = $trip->startDate;
        $this->endDate = $trip->endDate;
    }
}