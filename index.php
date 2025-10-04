<?php
// Set the response type to JSON so the client knows the data format
header("Content-Type: application/json");

// Include the database connection file
require 'db.php';

// Capture the HTTP request method (GET, POST, etc.)
$method = $_SERVER['REQUEST_METHOD'];

// Switch statement to handle different request methods
switch ($method) {
    case "GET":
        // Run a SQL query to fetch all products (id, name, price) sorted by latest
        $stmt = $pdo->query("SELECT id, name, price FROM products ORDER BY id DESC");
        // Convert the result set into JSON and send it as response
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case "POST":
        //
        // Get the raw JSON input from the request body and decode it into an array
        //
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate: name should not be empty and price must exist
        if (empty($data['name']) || !isset($data['price'])) {
            // Return HTTP 400 Bad Request if validation fails
            http_response_code(400);
            echo json_encode(["error" => "Name and price are required."]);
            exit;
        }

        // Validate: price should be numeric
        if (!is_numeric($data['price'])) {
            http_response_code(400);
            echo json_encode(["error" => "Price must be numeric."]);
            exit;
        }

        // Prepare SQL query to insert a new product
        $stmt = $pdo->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        // Execute the query with provided name and price
        $stmt->execute([$data['name'], $data['price']]);

        // Return a success response including the new product’s data
        echo json_encode([
            "success" => true,
            "id" => $pdo->lastInsertId(), // Get the ID of the newly inserted product
            "name" => $data['name'],
            "price" => $data['price']
        ]);
        break;

    default:
        // If request method is not GET or POST, return HTTP 405 Method Not Allowed
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
}
