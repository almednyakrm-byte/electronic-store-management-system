**edit_المنتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/المنتجات.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#product_name').val(data.product_name);
                $('#product_description').val(data.product_description);
                $('#product_price').val(data.product_price);
            }
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";

// Form HTML
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل المنتج</h2>
    <form id="edit-product-form" class="space-y-4">
        <div>
            <label for="product_name" class="block text-sm font-medium text-gray-700">اسم المنتج</label>
            <input type="text" id="product_name" name="product_name" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="اسم المنتج">
        </div>
        <div>
            <label for="product_description" class="block text-sm font-medium text-gray-700">وصف المنتج</label>
            <textarea id="product_description" name="product_description" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="وصف المنتج"></textarea>
        </div>
        <div>
            <label for="product_price" class="block text-sm font-medium text-gray-700">سعر المنتج</label>
            <input type="number" id="product_price" name="product_price" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="سعر المنتج">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</button>
    </form>
</div>

<?php
// JavaScript code for form submission
$js = "
    $(document).ready(function() {
        $('#edit-product-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/المنتجات.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'list_المنتجات.php';
                    } else {
                        alert('Error updating product');
                    }
                }
            });
        });
    });
";

// Include JavaScript code
echo "<script>$js</script>";
?>


**backend/المنتجات.php**

<?php
// Get product ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Fetch product details
$stmt = $conn->prepare('SELECT * FROM products WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->execute();
$product = $stmt->fetch();

// Return product details as JSON
header('Content-Type: application/json');
echo json_encode($product);
?>


**backend/المنتجات.php (update product)**

<?php
// Get product ID from URL
$id = $_GET['id'];

// Get product data from request
$product_name = $_POST['product_name'];
$product_description = $_POST['product_description'];
$product_price = $_POST['product_price'];

// Connect to database
$conn = new PDO('mysql:host=localhost;dbname=database', 'username', 'password');

// Update product details
$stmt = $conn->prepare('UPDATE products SET product_name = :product_name, product_description = :product_description, product_price = :product_price WHERE id = :id');
$stmt->bindParam(':id', $id);
$stmt->bindParam(':product_name', $product_name);
$stmt->bindParam(':product_description', $product_description);
$stmt->bindParam(':product_price', $product_price);
$stmt->execute();

// Return success message as JSON
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>