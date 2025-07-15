<?php
require_once '../vendor/autoload.php';
use Uph\Mobilsecond\DB;

$db = DB::getDB();

// Get form data
$id = $_POST['id'];
$name = htmlspecialchars($_POST['name']);
$address = htmlspecialchars($_POST['address']);
$phone = htmlspecialchars($_POST['phone']);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$opening_hours = htmlspecialchars($_POST['opening_hours']);
$status = in_array($_POST['status'], ['active', 'renovation', 'opening_soon', 'permanent_closed']) 
          ? $_POST['status'] 
          : 'active';

// Handle file upload
$image_url = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/showrooms/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $fileType = $_FILES['image']['type'];
    
    if (in_array($fileType, $allowedTypes)) {
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = 'showroom-' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image_url = 'uploads/showrooms/' . $filename;
            
            // Delete old image if exists
            $stmt = $db->prepare("SELECT image_url FROM showrooms WHERE id = ?");
            $stmt->execute([$id]);
            $oldImage = $stmt->fetchColumn();
            
            if ($oldImage && file_exists('../' . $oldImage)) {
                unlink('../' . $oldImage);
            }
        }
    }
}

try {
    if ($image_url) {
        $stmt = $db->prepare("
            UPDATE showrooms SET
                name = :name,
                address = :address,
                phone = :phone,
                email = :email,
                opening_hours = :opening_hours,
                status = :status,
                image_url = :image_url,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':phone' => $phone,
            ':email' => $email,
            ':opening_hours' => $opening_hours,
            ':status' => $status,
            ':image_url' => $image_url,
            ':id' => $id
        ]);
    } else {
        $stmt = $db->prepare("
            UPDATE showrooms SET
                name = :name,
                address = :address,
                phone = :phone,
                email = :email,
                opening_hours = :opening_hours,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([
            ':name' => $name,
            ':address' => $address,
            ':phone' => $phone,
            ':email' => $email,
            ':opening_hours' => $opening_hours,
            ':status' => $status,
            ':id' => $id
        ]);
    }
    
    header('Location: admin-showroom.php?success=updated');
    exit;
    
} catch (PDOException $e) {
    error_log('Showroom Update Error: ' . $e->getMessage());
    header('Location: admin-edit-showroom.php?id=' . $id . '&error=1');
    exit;
}