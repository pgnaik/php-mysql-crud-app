<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

require './db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    case 'GET':
        // Fetch all students
        $sql = "SELECT * FROM students";
        $result = $conn->query($sql);

        $students = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }
        }

        echo json_encode($students);
        break;

    case 'POST':
        // Create new student
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['name'], $data['email'], $data['course'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $course = $conn->real_escape_string($data['course']);

        $sql = "INSERT INTO students (name, email, course) VALUES ('$name', '$email', '$course')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Student created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }
        break;

    case 'PUT':
        // Update student
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['id'], $data['name'], $data['email'], $data['course'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        $id = (int)$data['id'];
        $name = $conn->real_escape_string($data['name']);
        $email = $conn->real_escape_string($data['email']);
        $course = $conn->real_escape_string($data['course']);

        $sql = "UPDATE students 
                SET name = '$name', email = '$email', course = '$course' 
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Student updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }
        break;

    case 'DELETE':
        // Delete student
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $id = (int) $_GET['id'];

        $sql = "DELETE FROM students WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['message' => 'Student deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error: ' . $conn->error]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}

$conn->close();
