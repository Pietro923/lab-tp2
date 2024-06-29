<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
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
        $stmt = $db->prepare("INSERT INTO alumno (Legajo, NombreCompleto, DNI, Telefono, Email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $data['Legajo'], $data['NombreCompleto'], $data['DNI'], $data['Telefono'], $data['Email']);
        $stmt->execute();
        echo json_encode(["status" => "success", "inserted_id" => $stmt->insert_id]);
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("UPDATE alumno SET NombreCompleto=?, DNI=?, Telefono=?, Email=? WHERE Legajo=?");
        $stmt->bind_param("ssssi", $data['NombreCompleto'], $data['DNI'], $data['Telefono'], $data['Email'], $data['Legajo']);
        $stmt->execute();
        echo json_encode(["status" => "success", "affected_rows" => $stmt->affected_rows]);
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $db->prepare("DELETE FROM alumno WHERE Legajo=?");
        $stmt->bind_param("i", $data['Legajo']);
        $stmt->execute();
        echo json_encode(["status" => "success", "affected_rows" => $stmt->affected_rows]);
        break;
    case 'GET':
        $result = $db->query("SELECT * FROM alumno");
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
