**edit_منتجات.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = '
    $(document).ready(function() {
        $.get("../backend/منتجات.php?id=' . $id . '")
            .done(function(data) {
                $("#name").val(data.name);
                $("#price").val(data.price);
                $("#description").val(data.description);
            })
            .fail(function() {
                alert("Error fetching product details");
            });
    });
';

// Include JavaScript code
echo '<script>' . $js . '</script>';

?>

<!-- Edit Product Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Product</h2>
    <form id="edit-product-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-slate-900">Price</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-600 focus:outline-none focus:ring-4 focus:ring-indigo-300">Update Product</button>
    </form>
</div>

<script>
    // Submit form via AJAX
    $(document).ready(function() {
        $('#edit-product-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: '../backend/منتجات.php',
                data: formData,
                success: function(data) {
                    if (data.success) {
                        window.location.href = "list_<?= $_SESSION['mod_slug'] ?>.php";
                    } else {
                        alert("Error updating product");
                    }
                },
                error: function() {
                    alert("Error updating product");
                }
            });
        });
    });
</script>


**backend/منتجات.php**

<?php
// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details from database
$product = fetchProduct($id);

// Update product details
if (isset($_POST['name']) && isset($_POST['price']) && isset($_POST['description'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Update product in database
    updateProduct($id, $name, $price, $description);

    // Return success message
    echo json_encode(['success' => true]);
} else {
    // Return product details
    echo json_encode($product);
}

// Helper functions
function fetchProduct($id) {
    // Fetch product details from database
    // ...
}

function updateProduct($id, $name, $price, $description) {
    // Update product in database
    // ...
}
?>