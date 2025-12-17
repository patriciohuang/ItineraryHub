<?php

namespace App\Models;

class Attachment {
    public int $id;
    public int $trip_item_id;
    public string $file_path;
    public string $type;
}