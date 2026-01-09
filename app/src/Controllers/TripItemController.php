<?php

namespace App\Controllers;

use App\Services\TripItemService;

class TripItemController
{
    private TripItemService $tripItemService;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $this->tripItemService = new TripItemService();
    }

    public function addTripItem(array $params)
    {
        $tripId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        $title = $_POST['title'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $url = $_POST['url'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $categoryId = $_POST['category_id'] ?? null;

        if (empty($title) || empty($startDate)) {
            $_SESSION['error'] = "Title and Start Date are required.";
            $_SESSION['error_add_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }
        if (strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = "Please ensure the dates are correct.";
            $_SESSION['error_add_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            $_SESSION['error'] = "Category is required.";
            $_SESSION['error_add_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }

        try {
            $newItemId = $this->tripItemService->createTripItem($tripId, (int)$categoryId, $title, $startDate, $endDate, $url, $notes, $userId);
            
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
                
                $fileTmpPath = $_FILES['attachment']['tmp_name'];
                $fileName = $_FILES['attachment']['name'];
                $fileType = $_FILES['attachment']['type'];

                $newFileName = uniqid() . '_' . $fileName;
                
                $uploadDir = __DIR__ . '/../../public/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $destPath = $uploadDir . $newFileName;

                if(move_uploaded_file($fileTmpPath, $destPath)) {
                    $webPath = '/uploads/' . $newFileName;
                    $this->tripItemService->addAttachment($newItemId, $webPath, $fileType);
                }
            }
            $_SESSION['success'] = "Item added successfully!";
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error adding item: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }

    public function showTripItemDetail(array $params)
    {
        $itemId = (int) $params['id'];
        $item = $this->tripItemService->getTripItemById($itemId);
        $categories = $this->tripItemService->getAllCategories();
        $attachment = $this->tripItemService->getAttachmentsByTripItemId($itemId);

        $oldInput = $_SESSION['form_input'] ?? [];
        unset($_SESSION['form_input']);

        $currentUserId = $_SESSION['user_id'] ?? 0;
        $isOwner = ($item->created_by === $currentUserId);

        if (!$item) {
            $_SESSION['error'] = "Item not found.";
            header("Location: /trip/$item->trip_id");
            exit;
        }

        require __DIR__ . '/../Views/trip-item/trip-item-detail.php';
    }

    public function editTripItem(array $params)
    {
        $itemId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        $title = $_POST['title'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];
        $url = $_POST['url'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $categoryId = $_POST['category_id'] ?? null;

        if (empty($title) || empty($startDate)) {
            $_SESSION['error'] = "Title and Start Date are required.";
            $_SESSION['error_edit_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/item/$itemId");
            exit;
        }
        if (strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = "Please ensure the dates are correct.";
            $_SESSION['error_edit_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/item/$itemId");
            exit;
        }
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            $_SESSION['error'] = "Category is required.";
            $_SESSION['error_edit_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/item/$itemId");
            exit;
        }

        try {
            $this->tripItemService->updateTripItem($itemId, (int)$categoryId, $title, $startDate, $endDate, $url, $notes, $userId);
            
            $_SESSION['success'] = "Item updated successfully!";
            header("Location: /trip/item/$itemId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error updating item: " . $e->getMessage();
            header("Location: /trip/item/$itemId");
            exit;
        }
    }

    public function deleteTripItem(array $params)
    {
        $itemId = (int) $params['id'];
        $userId = $_SESSION['user_id'];
        $item = $this->tripItemService->getTripItemById($itemId);
        $tripId = $item->trip_id;

        try {
            $this->tripItemService->deleteTripItem($userId, $itemId);

            $_SESSION['success'] = "Item deleted successfully!";
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error deleting item: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }
}