<?php

namespace App\Controllers;

use App\Services\TripItemService;
use App\Services\MembershipService;
use App\Services\TripService;

class TripItemController
{
    private TripItemService $tripItemService;
    private MembershipService $membershipService;
    private TripService $tripService;
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
        $this->membershipService = new MembershipService();
        $this->tripService = new TripService();
    }

    public function addTripItem(array $params)
    {
        $tripId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        $title = $_POST['title'];
        $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $url = $_POST['url'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $categoryId = $_POST['category_id'] ?? null;

        if (empty($title)) {
            $_SESSION['error'] = "Title is required.";
            $_SESSION['error_add_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }
        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
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
        $ownerTrip = $this->tripService->getTripById($item->trip_id);
        $oldInput = $_SESSION['form_input'] ?? [];
        unset($_SESSION['form_input']);

        $currentUserId = $_SESSION['user_id'] ?? 0;
        $isOwner = ($ownerTrip->added_by === $currentUserId);

        $participants = $this->tripItemService->getParticipantsByItemId($itemId);
        $allTripMembers = $this->membershipService->getMembersByTripId($item->trip_id);

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
        $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $url = $_POST['url'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $categoryId = $_POST['category_id'] ?? null;

        if (empty($title)) {
            $_SESSION['error'] = "Title is required.";
            $_SESSION['error_edit_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/item/$itemId");
            exit;
        }
        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
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
                    $existingAttachment = $this->tripItemService->getAttachmentsByTripItemId($itemId);

                    if ($existingAttachment) {
                        $oldFilePath = __DIR__ . '/../../public' . $existingAttachment->file_path;

                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                        $this->tripItemService->updateAttachment($itemId, $webPath, $fileType);
                    } else {    
                        $this->tripItemService->addAttachment($itemId, $webPath, $fileType);
                    }
                }
            }
            
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

    public function suggestItem(array $params)
    {
        $tripId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        $title = $_POST['title'];
        $startDate = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
        $endDate = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
        $url = $_POST['url'] ?? '';
        $notes = $_POST['notes'] ?? '';
        $categoryId = $_POST['category_id'] ?? null;

        if (empty($title)) {
            $_SESSION['error'] = "Title is required.";
            $_SESSION['error_add_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }
        if ($startDate && $endDate && strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = "Please ensure the dates are correct.";
            $_SESSION['error_add_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            $_SESSION['error'] = "Category is required.";
            $_SESSION['error_suggest_item'] = true;
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId");
            exit;
        }

        try {
            $this->tripItemService->suggestItem($tripId, (int)$categoryId, $title, $startDate, $endDate, $url, $notes, $userId);
            
            $_SESSION['success'] = "Item suggestion submitted for review!";
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error suggesting item: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }

    public function processSuggestedItem(array $params)
    {
        $itemId = (int) $params['id'];
        $action = $_POST['decision'] ?? 'reject';
        $userId = $_SESSION['user_id'];
        $item = $this->tripItemService->getTripItemById($itemId);
        $tripId = $item->trip_id;
        try {
            if ($action === 'approve') {
                $this->tripItemService->approveSuggestedItem($itemId, $userId);
                $_SESSION['success'] = "Item suggestion approved!";
            } elseif ($action === 'reject') {
                $this->tripItemService->rejectSuggestedItem($itemId, $userId);
                $_SESSION['success'] = "Item suggestion rejected.";
            }
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error processing suggestion: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }

    public function addParticipantToItem(array $params)
    {
        $itemId = (int) $params['id'];
        $userId = $_SESSION['user_id'];
        $memeberUserId = (int) $_POST['user_id'];
        $item = $this->tripItemService->getTripItemById($itemId);
        if($item->created_by !== $userId) {
            $_SESSION['error'] = "You do not have permission to add participants to this item.";
            header("Location: /trip/item/$itemId");
            exit;
        } else {
            $this->tripItemService->addParticipantToItem($itemId, $memeberUserId);
            $_SESSION['success'] = "You have been added a participant to this item.";
            header("Location: /trip/item/$itemId");
            exit;
        }
    }

    public function removeParticipantFromItem(array $params)
    {
        $itemId = (int) $params['id'];
        $userId = $_SESSION['user_id'];
        $memberUserId = (int) $_POST['user_id'];
        $item = $this->tripItemService->getTripItemById($itemId);
        if($item->created_by !== $userId) {
            $_SESSION['error'] = "You do not have permission to remove participants from this item.";
            header("Location: /trip/item/$itemId");
            exit;
        } else {
            $this->tripItemService->removeParticipantFromItem($itemId, $memberUserId);
            $_SESSION['success'] = "Participant removed from this item.";
            header("Location: /trip/item/$itemId");
            exit;
        }
    }
}