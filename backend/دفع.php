<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true) ?: $_POST;

// Validate input data
if (!isset($input['id']) && !isset($input['name']) && !isset($input['amount'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Sanitize input data
$input['id'] = (int) $input['id'] ?? 0;
$input['name'] = trim($input['name'] ?? '');
$input['amount'] = (float) $input['amount'] ?? 0;

// Connect to database
$db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// GET operation
if (isset($_GET['action']) && $_GET['action'] == 'get') {
    $stmt = $db->prepare('SELECT * FROM دفع WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        http_response_code(200);
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'list') {
    $stmt = $db->prepare('SELECT * FROM دفع');
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($results);
} elseif (isset($_GET['action']) && $_GET['action'] == 'count') {
    $stmt = $db->prepare('SELECT COUNT(*) as count FROM دفع');
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($result);
}

// POST operation
elseif (isset($_GET['action']) && $_GET['action'] == 'create') {
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $db->prepare('INSERT INTO دفع (name, amount) VALUES (:name, :amount)');
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':amount', $input['amount']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// PUT operation
elseif (isset($_GET['action']) && $_GET['action'] == 'update') {
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $db->prepare('UPDATE دفع SET name = :name, amount = :amount WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':amount', $input['amount']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// DELETE operation
elseif (isset($_GET['action']) && $_GET['action'] == 'delete') {
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    $stmt = $db->prepare('DELETE FROM دفع WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Close database connection
$db = null;

// Set response headers
header('Content-Type: application/json');