<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Use POST"]);
    exit();
}

require_once "../connection.php";
$con = connection();

$input_raw = file_get_contents("php://input");
$input = json_decode($input_raw, true) ?? [];

$username = trim($input['username'] ?? $_POST['username'] ?? '');
$password = $input['password'] ?? $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Usuario y contraseña son requeridos"]);
    exit();
}

$stmt = $con->prepare("SELECT id, name, lastname, username, email, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $authenticated = false;

    // Validación por hash o texto plano antiguo
    if (password_verify($password, $row['password'])) {
        $authenticated = true;
    } else if ($password === $row['password']) {
        $authenticated = true;
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        $update_stmt = $con->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_hash, $row['id']);
        $update_stmt->execute();
        $update_stmt->close();
    }

    if ($authenticated) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Autenticación exitosa",
            "data" => [
                "id" => $row['id'],
                "name" => $row['name'],
                "lastname" => $row['lastname'],
                "username" => $row['username'],
                "email" => $row['email']
            ]
        ]);
        $stmt->close();
        $con->close();
        exit();
    }
}

http_response_code(401);
echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);

$stmt->close();
$con->close();
?>

