<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array(
        '/products' => 'getProducts',
        '/products/:id' => 'getProduct',
    ),
    'POST' => array(
        '/products' => 'createProduct',
    ),
    'PUT' => array(
        '/products/:id' => 'updateProduct',
    ),
    'DELETE' => array(
        '/products/:id' => 'deleteProduct',
    ),
);

// Get route
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];
$route = explode('/', $path);
array_shift($route); // Remove empty string
array_shift($route); // Remove 'products'

// Check if route exists
if (!isset($routes[$method][$route[0]])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
    exit;
}

// Call route function
$func = $routes[$method][$route[0]];
$func();

// Helper functions
function getProducts() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM products');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($products);
}

function getProduct() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$product) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($product);
}

function createProduct() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);
    // Insert product
    $stmt = $pdo->prepare('INSERT INTO products (name, price) VALUES (:name, :price)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Product created successfully'));
}

function updateProduct() {
    global $pdo;
    $id = $_GET['id'];
    // Validate input
    if (!isset($input['name']) || !isset($input['price'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    // Sanitize input
    $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT);
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update product
    $stmt = $pdo->prepare('UPDATE products SET name = :name, price = :price WHERE id = :id');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Product updated successfully'));
}

function deleteProduct() {
    global $pdo;
    $id = $_GET['id'];
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete product
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Product deleted successfully'));
}