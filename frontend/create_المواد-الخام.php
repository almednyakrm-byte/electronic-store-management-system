**create_المواد-الخام.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    if (empty($name) || empty($description) || empty($quantity) || empty($unit_price)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO materials_raw (name, description, quantity, unit_price) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssds', $name, $description, $quantity, $unit_price);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            // Redirect back to list page
            header('Location: list_المواد-الخام.php');
            exit;
        } else {
            $error = 'Failed to create material';
        }
    }
}

// Include header
require_once '../includes/header.php';

?>

<!-- Create Material Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Material</h2>
    <form id="create-material-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="unit_price" class="block text-sm font-medium text-gray-700">Unit Price:</label>
            <input type="number" id="unit_price" name="unit_price" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-md">Create Material</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-material-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('../backend/المواد-الخام.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_المواد-الخام.php';
            } else {
                document.getElementById('error-message').innerHTML = data.error;
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/المواد-الخام.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $unit_price = trim($_POST['unit_price']);

    if (empty($name) || empty($description) || empty($quantity) || empty($unit_price)) {
        echo json_encode(['error' => 'Please fill in all fields']);
    } else {
        // Insert data into database
        $query = "INSERT INTO materials_raw (name, description, quantity, unit_price) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssds', $name, $description, $quantity, $unit_price);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to create material']);
        }
    }
}