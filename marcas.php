<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$method = $_SERVER['REQUEST_METHOD'];
$db = new mysqli("localhost", "root", "", "facultad");

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("INSERT INTO marcas (nombre) VALUES (?)");
        $stmt->bind_param("s", $data['nombre']);
        $stmt->execute();
        echo json_encode(["status" => "success"]);
        break;
    case 'GET':
        $result = $db->query("SELECT * FROM marcas");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
        break;
    default:
        echo json_encode(["status" => "error", "message" => "Unsupported request method"]);
        break;
}

$db->close();
?>
