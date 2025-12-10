<?php

namespace App\Models;

class Attachment {
    public int $id;
    public int $tripItemId;
    public string $filePath;
    public string $type;
}