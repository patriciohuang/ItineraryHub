<?php

namespace App\Models;

class Trip {
    public int $id;
    public string $title;
    public ?string $description = null;
    public string $start_date;
    public string $end_date;
    public int $added_by;
}