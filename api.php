<?php
header("Content-Type: application/json");
include 'api_db.php';  // Ensure this file properly sets up the $pdo variable

// Check if the PDO object exists and is an instance of the PDO class
if (!isset($pdo) || !$pdo instanceof PDO) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Fetch HTTP request method (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD'];

// Sanitize incoming data (if applicable)
function sanitizeInput($data)
{
    return htmlspecialchars(strip_tags($data));
}

// Validate required fields
function validateFields($data, $fields)
{
    foreach ($fields as $field) {
        if (empty($data->$field)) {
            return false;
        }
    }
    return true;
}

// Error response helper
function errorResponse($code, $message)
{
    http_response_code($code);
    echo json_encode([
        'status' => $code,
        'message' => $message,
        'data' => null
    ]);
    exit;
}

switch ($method) {
    case 'GET':
        // Validate ID for GET request (if provided)
        if (isset($_GET['userID'])) {
            $userID = intval($_GET['userID']);
            if ($userID <= 0) {
                errorResponse(400, "Invalid ID provided");
            }

            $stmt = $pdo->prepare("SELECT * FROM users WHERE userID = ?");
            $stmt->execute([$userID]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                http_response_code(200);
                echo json_encode($user);
            } else {
                errorResponse(404, "User not found");
            }
        } else {
            // No specific ID, return all users
            $stmt = $pdo->query("SELECT * FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            echo json_encode($users);
        }
        break;

    case 'POST':
        // Decode JSON input
        $data = json_decode(file_get_contents("php://input"));

        // Validate required fields: username, password, email
        if (!$data || !validateFields($data, ['username', 'password'])) {
            errorResponse(400, "Missing required fields");
        }

        // Sanitize inputs
        $username = sanitizeInput($data->username);
        $password = sanitizeInput($data->password);


        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $result = $stmt->execute([$username, $password]);

        if ($result) {
            http_response_code(201);
            echo json_encode(["success" => true, "message" => "User created successfully"]);
        } else {
            errorResponse(500, "Failed to create user");
        }
        break;

    case 'PUT':
        // Decode JSON input
        $data = json_decode(file_get_contents("php://input"));

        // Validate required fields: userID, username, password, email
        if (!$data || !validateFields($data, ['userID', 'username', 'password'])) {
            errorResponse(400, "Missing required fields");
        }

        // Sanitize inputs
        $userID = intval($data->userID);
        $username = sanitizeInput($data->username);
        $password = sanitizeInput($data->password);

        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, WHERE userID = ?");
        $result = $stmt->execute([$username, $password, $userID]);

        if ($result) {
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "User updated successfully"]);
        } else {
            errorResponse(500, "Failed to update user");
        }
        break;

    case 'DELETE':
        if (isset($_GET['userID'])) {
            $userID = intval($_GET['userID']);
            if ($userID <= 0) {
                errorResponse(400, "Invalid ID provided");
            }

            $stmt = $pdo->prepare("DELETE FROM users WHERE userID = ?");
            $result = $stmt->execute([$userID]);

            if ($result) {
                http_response_code(200);
                echo json_encode(["success" => true, "message" => "User deleted successfully"]);
            } else {
                errorResponse(404, "User not found");
            }
        } else {
            errorResponse(400, "ID is required for deletion");
        }
        break;

    default:
        errorResponse(405, "Method Not Allowed");
        break;
}