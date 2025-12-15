<?php

namespace App\Models;

class TripItem {
    public const STATUS_SUGGESTED = 'SUGGESTED';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_PUBLISHED = 'PUBLISHED';

    public int $id;
    public int $trip_id;
    public string $title;
    public string $start_date;
    public ?string $end_date;
    public ?string $url;
    public ?string $notes = null;
    public int $category_id;
    public string $status;
    public int $created_by;
    public string $category_name;
}