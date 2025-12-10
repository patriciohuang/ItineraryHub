<?php

namespace App\Models;

class TripItem {
    public const STATUS_SUGGESTED = 'SUGGESTED';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_PUBLISHED = 'PUBLISHED';

    public int $id;
    public int $tripId;
    public string $title;
    public string $startDate;
    public string $endDate;
    public string $url;
    public string $notes;
    public int $categoryId;
    public string $status;
    public int $createdBy;
}