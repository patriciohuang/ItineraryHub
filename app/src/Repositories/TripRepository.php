<?php

namespace App\Repositories;

use App\Framework\Repository;
use App\Repositories\ITripRepository;
use App\Models\Trip;
use App\Models\TripItem;
use App\Models\Attachment;

class TripRepository extends Repository implements ITripRepository
{
    //Trip methods
    public function getAllTrips(int $userId): array
    {
        $sql = 'SELECT id, title, description, start_date, end_date FROM trips WHERE added_by = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':user_id' => $userId]);
        return $statement->fetchAll(\PDO::FETCH_CLASS, Trip::class);
    }

    public function getTripById(int $userId, int $tripId): Trip
    {
        $sql = 'SELECT id, title, description, start_date, end_date, added_by FROM trips WHERE id = :trip_id AND added_by = :user_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, Trip::class);
        $trip = $statement->fetch();
        return $trip? : null;
    }

    public function updateTrip(int $tripId, string $title, string $description, string $startDate, string $endDate): void
    {
        $sql = 'UPDATE trips SET title = :title, description = :description, start_date = :start_date, end_date = :end_date WHERE id = :trip_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':trip_id' => $tripId
        ]);
    }
    
    public function createTrip(int $userId, string $title, string $description, string $startDate, string $endDate): void
    {
        $sql = 'INSERT INTO trips (title, description, start_date, end_date, added_by) VALUES (:title, :description, :start_date, :end_date, :added_by)';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':title' => $title,
            ':description' => $description,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':added_by' => $userId
        ]);
    }
    //Trip item methods
    public function getTripItems(int $userId, int $tripId): array
    {
        $sql = 'SELECT ti.id, ti.trip_id, ti.category_id, ti.title, ti.start_date, ti.end_date, ti.url, ti.notes, ti.created_by, c.name AS category_name
                FROM trip_items ti
                JOIN trips t ON ti.trip_id = t.id
                JOIN categories c ON ti.category_id = c.id
                WHERE ti.trip_id = :trip_id AND t.added_by = :user_id';
        
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':user_id' => $userId
        ]);
        
        return $statement->fetchAll(\PDO::FETCH_CLASS, \App\Models\TripItem::class);
    }
    
    public function createTripItem(int $tripId, int $categoryId, string $title, string $startDate, string $endDate, string $url, string $notes, int $userId): int
    {
        $sql = 'INSERT INTO trip_items 
                (trip_id, category_id, title, start_date, end_date, url, notes, created_by, status) 
                VALUES 
                (:trip_id, :category_id, :title, :start_date, :end_date, :url, :notes, :created_by, "APPROVED")';
        
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_id' => $tripId,
            ':category_id' => $categoryId,
            ':title' => $title,
            ':start_date' => $startDate,
            ':end_date' => $endDate,
            ':url' => $url,
            ':notes' => $notes,
            ':created_by' => $userId
        ]);
        return (int) $this->getConnection()->lastInsertId();
    }

    public function getTripItemById(int $tripItemId): TripItem
    {
        $sql = 'SELECT ti.id, ti.trip_id, ti.category_id, ti.title, ti.start_date, ti.end_date, ti.url, ti.notes, ti.created_by, c.name AS category_name
                FROM trip_items ti
                JOIN categories c ON ti.category_id = c.id
                WHERE ti.id = :trip_item_id';
        
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([
            ':trip_item_id' => $tripItemId,
        ]);
        
        $statement->setFetchMode(\PDO::FETCH_CLASS, \App\Models\TripItem::class);
        $tripItem = $statement->fetch();
        return $tripItem ? : null;
    }
    
    public function getAllCategories(): array
    {
        $sql = 'SELECT * FROM categories ORDER BY name ASC';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, \App\Models\Category::class);
    }

    public function addAttachment(int $tripItemId, string $filePath, string $type): void
    {
        $sql = 'INSERT INTO attachments (trip_item_id, file_path, type) VALUES (:item_id, :path, :type)';
        $stmt = $this->getConnection()->prepare($sql);
        $stmt->execute([
            ':item_id' => $tripItemId,
            ':path' => $filePath,
            ':type' => $type
        ]);
    }

    public function getAttachmentsByTripItemId(int $tripItemId): ?Attachment
    {
        $sql = 'SELECT * FROM attachments WHERE trip_item_id = :item_id';
        $statement = $this->getConnection()->prepare($sql);
        $statement->execute([':item_id' => $tripItemId]);
        $statement->setFetchMode(\PDO::FETCH_CLASS, \App\Models\Attachment::class);
        $attachment = $statement->fetch();
        return $attachment ? : null;
    }
}