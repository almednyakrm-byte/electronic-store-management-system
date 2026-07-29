<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all suppliers
    $stmt = $pdo->prepare('SELECT * FROM الموردين');
    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return suppliers
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($suppliers);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['اسم_المورد']) || !isset($input['عنوان_المورد']) || !isset($input['رقم_الهاتف']) || !isset($input['عنوان_البريد_الالكتروني'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($input['اسم_المورد'], FILTER_SANITIZE_STRING);
    $address = filter_var($input['عنوان_المورد'], FILTER_SANITIZE_STRING);
    $phone = filter_var($input['رقم_الهاتف'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($input['عنوان_البريد_الالكتروني'], FILTER_SANITIZE_EMAIL);

    // Insert supplier
    $stmt = $pdo->prepare('INSERT INTO الموردين (اسم_المورد, عنوان_المورد, رقم_الهاتف, عنوان_البريد_الالكتروني) VALUES (:name, :address, :phone, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Supplier created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['اسم_المورد']) || !isset($input['عنوان_المورد']) || !isset($input['رقم_الهاتف']) || !isset($input['عنوان_البريد_الالكتروني'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($input['اسم_المورد'], FILTER_SANITIZE_STRING);
    $address = filter_var($input['عنوان_المورد'], FILTER_SANITIZE_STRING);
    $phone = filter_var($input['رقم_الهاتف'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($input['عنوان_البريد_الالكتروني'], FILTER_SANITIZE_EMAIL);

    // Update supplier
    $stmt = $pdo->prepare('UPDATE الموردين SET اسم_المورد = :name, عنوان_المورد = :address, رقم_الهاتف = :phone, عنوان_البريد_الالكتروني = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Supplier updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete supplier
    $stmt = $pdo->prepare('DELETE FROM الموردين WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Supplier deleted successfully'));
    exit;
}