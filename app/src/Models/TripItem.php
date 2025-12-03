<?php

namespace App\Models;

class TripItem {
    public const STATUS_SUGGESTED = 'SUGGESTED';
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_PUBLISHED = 'PUBLISHED';

    public $id;
    public $tripId;
    public $title;
    public $startDate;
    public $endDate;
    public $url;
    public $notes;
    public $categoryId;
    public $status;
    public $createdBy;
}