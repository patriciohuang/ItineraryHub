<?php

namespace App\Controllers;

use App\Services\ITripService;
use App\Services\TripService;
use App\ViewModels\TripsViewModel;


class TripController
{
    private ITripService $tripService;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $this->tripService = new TripService();
    }

    public function home()
    {
        $userId = $_SESSION['user_id'];
        $trips = $this->tripService->getAllTrips($userId);
        $vm = new TripsViewModel($trips);
        require __DIR__ . '/../Views/trip/Home.php';
    }

    public function showAddTrip()
    {
        require __DIR__ . '/../Views/trip/Add-trip.php';
    }

    public function addTrip()
    {
        $userId = $_SESSION['user_id'];
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';

        if (empty($title)) {
            $_SESSION['error'] = 'Trip title is required.';
            header('Location: /trip/add');
            exit;
        }

        if (empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = 'Start date and end date are required.';
            header('Location: /trip/add');
            exit;
        }

        if (strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = 'Start date cannot be later than end date.';
            header('Location: /trip/add');
            exit;
        }
        try
        {
            $this->tripService->createTrip($userId, $title, $description, $startDate, $endDate);

            $_SESSION['success'] = 'Trip created successfully.';
            header('Location: /');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating trip: ' . $e->getMessage();
            header('Location: /trip/add');
            exit;
        }
    }

    public function seeTripDetail(array $params)
    {
        $id = $params['id'] ?? null;
        if ($id === null) {
            $_SESSION['error'] = 'Trip ID is required.';
            header('Location: /');
            exit;
        }
        try {
            $userId = $_SESSION['user_id'];
            $trip = $this->tripService->getTripById($userId, $id);
            $items = $this->tripService->getTripItems($userId, $id);
            require __DIR__ . '/../Views/trip/detail.php';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /');
            exit;
        }
    }

    public function editTripDetail(array $params)
    {
        $tripId = (int) $params['id'];
        $userId = $_SESSION['user_id'];

        $title = $_POST['title'];
        $description = $_POST['description'];
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        if (empty($title) || empty($startDate) || empty($endDate)) {
            $_SESSION['error'] = "Title and Dates are required.";
            header("Location: /trip/$tripId");
            exit;
        }

        try {
            $this->tripService->updateTrip($tripId, $title, $description, $startDate, $endDate);
            
            $_SESSION['success'] = "Trip updated successfully!";
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error updating trip: " . $e->getMessage();
            header("Location: /trip/$tripId");
            exit;
        }
    }

    public function showAddTripItem(array $params)
    {
        
        $tripId = (int) $params['id'];
        
        $categories = $this->tripService->getAllCategories();

        $oldInput = $_SESSION['form_input'] ?? [];
        unset($_SESSION['form_input']);
        
        require __DIR__ . '/../Views/trip/add-trip-item.php';
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
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId/item/add");
            exit;
        }
        if (strtotime($startDate) > strtotime($endDate)) {
            $_SESSION['error'] = "Please ensure the dates are correct.";
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId/item/add");
            exit;
        }
        if (!is_numeric($categoryId) || (int)$categoryId <= 0) {
            $_SESSION['error'] = "Category is required.";
            $_SESSION['form_input'] = $_POST;
            header("Location: /trip/$tripId/item/add");
            exit;
        }

        try {
            $newItemId = $this->tripService->createTripItem($tripId, (int)$categoryId, $title, $startDate, $endDate, $url, $notes, $userId);
            
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
                    $this->tripService->addAttachment($newItemId, $webPath, $fileType);
                }
            }
            $_SESSION['success'] = "Item added successfully!";
            header("Location: /trip/$tripId");
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = "Error adding item: " . $e->getMessage();
            header("Location: /trip/$tripId/item/add");
            exit;
        }
    }

    public function showTripItemDetail(array $params)
    {
        $itemId = (int) $params['id'];
        $item = $this->tripService->getTripItemById($itemId);
        $attachment = $this->tripService->getAttachmentsByTripItemId($itemId);
        if (!$item) {
            $_SESSION['error'] = "Item not found.";
            header("Location: /trip/$item->trip_id");
            exit;
        }

        require __DIR__ . '/../Views/trip/trip-item-detail.php';
    }
}