<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, send a JSON response with their details
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $response = array('status' => 'logged_in', 'user_id' => $user_id, 'username' => $username);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Check if the user is trying to register or login
if (isset($_POST['action'])) {
    // Check if the action is register
    if ($_POST['action'] == 'register') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Sanitize the input fields to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $email = mysqli_real_escape_string($conn, $_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Prepare the SQL query to insert the user details into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();

            // If the user is registered successfully, send a JSON response
            $response = array('status' => 'registered', 'message' => 'User registered successfully');
            header('Content-Type: application/json');
            echo json_encode($response);
        } else {
            // If any required field is missing, send a JSON response with an error message
            $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Check if the action is login
    elseif ($_POST['action'] == 'login') {
        // Check if all required fields are present
        if (isset($_POST['username']) && isset($_POST['password'])) {
            // Sanitize the input fields to prevent SQL injection
            $username = mysqli_real_escape_string($conn, $_POST['username']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);

            // Prepare the SQL query to select the user details from the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the user exists in the database
            if ($result->num_rows > 0) {
                // Fetch the user details from the database
                $row = $result->fetch_assoc();

                // Verify the password using password_verify()
                if (password_verify($password, $row['password'])) {
                    // If the password is correct, start a session for the user
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];

                    // Send a JSON response with the user details
                    $response = array('status' => 'logged_in', 'user_id' => $_SESSION['user_id'], 'username' => $_SESSION['username']);
                    header('Content-Type: application/json');
                    echo json_encode($response);
                } else {
                    // If the password is incorrect, send a JSON response with an error message
                    $response = array('status' => 'error', 'message' => 'Incorrect password');
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }
            } else {
                // If the user does not exist in the database, send a JSON response with an error message
                $response = array('status' => 'error', 'message' => 'User not found');
                header('Content-Type: application/json');
                echo json_encode($response);
            }
        } else {
            // If any required field is missing, send a JSON response with an error message
            $response = array('status' => 'error', 'message' => 'Please fill in all required fields');
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    // Check if the action is logout
    elseif ($_POST['action'] == 'logout') {
        // Destroy the session to log out the user
        session_destroy();

        // Send a JSON response with a success message
        $response = array('status' => 'logged_out', 'message' => 'User logged out successfully');
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>