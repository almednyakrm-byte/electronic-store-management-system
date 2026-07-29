<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get product ID from URL parameter
    $productID = $_GET['id'] ?? null;

    // Validate product ID
    if (!$productID) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product ID']);
        exit;
    }

    // Select product by ID
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $productID);
    $stmt->execute();
    $product = $stmt->fetch();

    // Check if product exists
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
        exit;
    }

    // Return product data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($product);
}

// Handle POST request
elseif ($method === 'POST') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get product data from request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate product data
    if (!$requestData || !isset($requestData['name'], $requestData['description'], $requestData['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product data']);
        exit;
    }

    // Sanitize product data
    $name = trim($requestData['name']);
    $description = trim($requestData['description']);
    $price = (float) $requestData['price'];

    // Insert product into database
    $stmt = $pdo->prepare('INSERT INTO products (name, description, price) VALUES (:name, :description, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return product ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get product ID from URL parameter
    $productID = $_GET['id'] ?? null;

    // Validate product ID
    if (!$productID) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product ID']);
        exit;
    }

    // Get product data from request body
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Validate product data
    if (!$requestData || !isset($requestData['name'], $requestData['description'], $requestData['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product data']);
        exit;
    }

    // Sanitize product data
    $name = trim($requestData['name']);
    $description = trim($requestData['description']);
    $price = (float) $requestData['price'];

    // Update product in database
    $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $productID);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product updated successfully']);
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Validate user role
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get product ID from URL parameter
    $productID = $_GET['id'] ?? null;

    // Validate product ID
    if (!$productID) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid product ID']);
        exit;
    }

    // Delete product from database
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->bindParam(':id', $productID);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Product deleted successfully']);
}