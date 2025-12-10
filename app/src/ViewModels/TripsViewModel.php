<?php

namespace App\ViewModels;

use App\Models\Trip;

class TripsViewModel
{
    /** @var Trip[] $trips */
    public array $trips;

    public function __construct(array $trips)
    {
        $this->trips = $trips;
    }
}